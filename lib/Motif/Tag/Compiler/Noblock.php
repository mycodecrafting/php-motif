<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Compile <motif:noblock var="varName">
 */
class Motif_Tag_Compiler_Noblock extends Motif_Tag_Compiler_Abstract
{

    /**
     * @var string Tag's name
     */
    protected $_tagName = 'noblock';

    /**
     * @var boolean Tag has pairs? (opening and closing)
     */
    protected $_hasTagPairs = true;

    /**
     * Declare attributes for this tag
     *
     * @return void
     */
    protected function _declareAttributes()
    {
        $this->_attributes = array(
            'var' => new Motif_Tag_Attribute_Required(self::MATCH_VAR),
        );
    }

    /**
     * Compile tag matches to native PHP code
     */
    protected function _compileMatches()
    {
        /**
         * Parse opening tags
         */
        foreach ($this->_tagMatches as $match)
        {
            $varCode = $this->_parseVarName($this->getAttribute('var'));

        	$code = '' .
        	    '\');' . NL .
        		"if (!isset({$varCode}) || empty({$varCode}))" . NL .
        		'{' . NL .
        		'echo(\'';

            /**
             * Do replacement
             */
            $this->_replaceCode($code);
        }

        /**
         * Parse closing tags
         */
        $code = '' .
            '\');' . NL .
            '}' . NL .
            'echo(\'';

        $this->_replaceCode($code, self::CLOSING_TAGS);
    }

}
