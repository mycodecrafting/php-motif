<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


abstract class Motif_Tag_Compiler_Abstract
{

    /**
     * @var string Tag's name
     */
    protected $_tagName = 'tagname';

    /**
     * @var boolean Tag has pairs? (opening and closing)
     */
    protected $_hasTagPairs = true;

    /**
     * @var array Tag attributes
     */
    protected $_attributes = array();

    /**
     * @var Motif_Tag_Matches_Iterator
     */
    protected $_tagMatches;

    /**
     * @var string
     */
    protected $_code;

    /**
     * @var string The current match
     */
    protected $_match;

    /**
     * @const Opening tags internal identifier
     */
    const OPENING_TAGS = '__OPENING_TAGS__';

    /**
     * @const Closing tags internal identifier
     */
    const CLOSING_TAGS = '__CLOSING_TAGS__';

    const MATCH_VAR = '[A-z0-9_.]+';
    const MATCH_WILDCARD = '[^"]+';
    const MATCH_CONDITION = '(not)?exists|>|gt|>=|gte|<|lt|<=|lte|<>|\!=|=|(not)?equal';

    public function __construct(array $tagMatches = array())
    {
        $this->_tagMatches = new Motif_Tag_Matches_Iterator($tagMatches, $this);
        $this->_declareAttributes();
    }

    /**
     * Compile tags to native PHP code
     */
    public function compile($code)
    {
        $this->_checkTagCount($code);

        $this->_code = $code;

        $this->_compileMatches();

        return $this->_code;
    }

    /**
     * Get the tag's name
     *
     * @return string Tag's name
     */
    public function getTagName()
    {
        return $this->_tagName;
    }

    /**
     * Tag has pairs? (opening and closing)
     *
     * @return boolean
     */
    public function hasTagPairs()
    {
        return $this->_hasTagPairs;
    }

    /**
     * Get the current match
     *
     * @return string
     */
    public function getMatch()
    {
        return $this->_match;
    }

    /**
     * Set the current match
     *
     * @param string $match
     * @return void
     */
    public function setMatch($match)
    {
        $this->_match = $match;
    }

    public function checkAttributes()
    {
        foreach ($this->getAttributes() as $name => $attribute)
        {
            $this->getAttribute($name);
        }
    }

    public function getAttributes()
    {
        return $this->_attributes;
    }

    public function getAttribute($name)
    {
        if (!isset($this->_attributes[$name]))
        {
            $this->_throwCompilationError(sprintf(
                'Cannot access undeclared attribute "%s"', $name
            ));
            return false;
        }

        /**
         * Check if the attribute exists at all
         */
        $search = '/(\s+)' . $name . '="(.*)"/U';

        if (preg_match($search, $this->getMatch(), $matches1) === 0)
        {
            if (($this->_attributes[$name] instanceof Motif_Tag_Attribute_Required) === true)
            {
                $this->_throwCompilationError(sprintf(
                    'Missing required attribute "%s"', $name
                ));
            }

            return $this->_attributes[$name]->getDefault();
        }

        /**
         * Check if the pattern matches
         */
        $search = '/(\s+)' . $name . '="(' . $this->_attributes[$name]->getPattern() . ')"/U';

        if (preg_match($search, $this->getMatch(), $matches) === 0)
        {
            $this->_throwCompilationError(sprintf(
                'Invalid value "%s" for attribute "%s"', $matches1[2], $name
            ));

            return false;
        }

        return $matches1[2];
    }



    protected function _getAttributeString()
    {
		$search = '/<motif:' . $this->getTagName() . '((\s+)(.+))\/?>/U';

		if (preg_match($search, $this->getMatch(), $matches) === 0)
		{
			return false;
		}

		$attributes = $matches[1];

        /**
         * Exclude passed attributes
         */
		foreach (func_get_args() as $name)
		{
			$search = '/(\s+)' . $name . '="(.*)"/U';
			$attributes = preg_replace($search, '', $attributes);
		}

        return $attributes;
    }

    /**
     * Check if a value matches the pattern for template vars
     *
     * @param string $value
     * @return boolean
     */
    protected function _isVarMatch($value)
    {
        return (preg_match('/^(' . self::MATCH_VAR . ')$/', $value) !== 0);
    }

    /**
     * Check that opening tags & closing tags match in count
     */
    protected function _checkTagCount($code)
    {
        if ($this->hasTagPairs() === true)
        {
            if ($this->_tagMatches->count() !== substr_count($code, '</motif:' . $this->getTagName() . '>'))
            {
                $this->_throwCompilationError('Tag count mismatch');
                return false;
            }
        }

        return true;
    }

    /**
     * Replace motif code with native PHP generated code
     *
     */
    protected function _replaceCode($replacement, $search = false)
    {
        switch ($search)
        {
            case false:
                $search = sprintf('/(\/\*)?%s(\*\/)?/', preg_quote($this->getMatch(), '/'));
                break;

            case self::OPENING_TAGS:
                $search = sprintf('/(\/\*)?<motif:%s>(\*\/)?/', $this->getTagName());
                break;

            case self::CLOSING_TAGS:
                $search = sprintf('/(\/\*)?<\/motif:%s>(\*\/)?/', $this->getTagName());
                break;

            default:
                break;
        }

        $this->_code = preg_replace($search, $replacement, $this->_code);
    }

    /**
     * Parse a template variable to native PHP code
     */
    protected function _parseVarName($var)
    {
        /**
         * Default var code
         */
        $code = '$___vars';

        /**
         * Parse "top.varName" vars
         */
        if (substr($var, 0, 4) === 'top.')
        {
            $code = '$___stack[0]';
            $var = substr($var, 4);
        }
        else
        {
            $parentLevel = 0;

            /**
             * Parse "parent.varName", "parent.parent.varName", etc vars in blocks & nested blocks
             */
            while (substr($var, 0, 7) === 'parent.')
            {
                $var = substr($var, 7);
                ++$parentLevel;
            }

            if ($parentLevel > 0)
            {
                $code = '$___stack[$___stackCount-' . $parentLevel . ']';
            }
        }

        /**
         * Parse varName.innerVar vars
         */
        while (strpos($var, '.') !== false)
        {
            list($parent, $var) = explode('.', $var, 2);

            if (is_numeric($parent))
            {
                $code .= sprintf('[%s]', $parent);
            }
            else
            {
                $code .= sprintf("['%s']", $parent);
            }
        }

        $code .= sprintf("['%s']", $var);

        return $code;
    }

    /**
     * Parse the condition attribute of the tag
     *
     * Valid conditions:
     *      equal, =            : equal to (default)
     *      notequal, !=        : not equal to
     *      gt, >               : greater than
     *      gte, >=             : greater than or equal to
     *      lt, <               : less than
     *      lte, <=             : less than or equal to
     *      exists              : var does exist
     *      notexists           : var does not exist
     */
    protected function _parseCondition()
    {
        $condition = $this->getAttribute('condition');

        switch ($condition)
        {
            case 'exists':
                $condition = 'exists';
                break;

            case 'notexists':
                $condition = 'notexists';
                break;

            case '>':
            case 'gt':
                $condition = '>';
                break;

            case '>=':
            case 'gte':
                $condition = '>=';
                break;

            case '<':
            case 'lt':
                $condition = '<';
                break;

            case '<=':
            case 'lte':
                $condition = '<=';
                break;

            case '!=':
            case '<>':
            case 'notequal':
                $condition = '!=';
                break;

            case '=':
            case 'equal':
            default:
                $condition = '==';
                break;
        }

        return $condition;
    }

    /**
     * Throws error that occured during template compilation
     *
     * @param string $message Error message
     * @throws Motif_Tag_Compiler_Exception
     * @return void
     */
    protected function _throwCompilationError($message)
    {
        throw new Motif_Tag_Compiler_Exception(sprintf(
            '<em>&lt;motif:%s&gt;:</em> %s', $this->getTagName(), $message
        ));
    }

    /**
     * Declare attributes for this tag
     *
     * @return void
     */
    abstract protected function _declareAttributes();

    /**
     * Compile tag matches to native PHP code
     */
    abstract protected function _compileMatches();

}
