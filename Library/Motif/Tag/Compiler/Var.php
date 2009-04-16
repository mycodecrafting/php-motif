<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Compile <motif:var name="abc" value="123" [isvar="isvar"] />
 */
class Motif_Tag_Compiler_Var extends Motif_Tag_Compiler_Abstract
{

    /**
     * @var string Tag's name
     */
    protected $_tagName = 'var';

    /**
     * @var boolean Tag has pairs? (opening and closing)
     */
    protected $_hasTagPairs = false;

    /**
     * Declare attributes for this tag
     *
     * @return void
     */
    protected function _declareAttributes()
    {
		$this->_attributes = array(
			'name'  => new Motif_Tag_Attribute_Required(self::MATCH_VAR),
			'value' => new Motif_Tag_Attribute_Required(self::MATCH_WILDCARD),
			'isvar' => new Motif_Tag_Attribute('isvar'),
		);
    }

    /**
     * Compile tag matches to native PHP code
     */
    protected function _compileMatches()
    {
        foreach ($this->_tagMatches as $match)
        {
            $nameCode = $this->_parseVarName($this->getAttribute('name'));
            $value = $this->getAttribute('value');

            /**
             * Setting new var to an existing template var:
             *   <motif:var name="newVar" value="existingVar" isvar="isvar" />
             */
            if ($this->getAttribute('isvar') && $this->_isVarMatch($value))
            {
                $value = $this->_parseVarName($value);

                $code = '' .
                    '\');' . NL .
                    "{$nameCode} = {$value};" . NL .
                    'echo(\'';
            }

            /**
             * Setting var to a given value
             *   <motif:var name="newVar" value="Hello World!" />
             */
            else
            {
                $code = '' .
                    '\');' . NL .
                    'ob_start();' . NL .
                    "echo('{$value}');" . NL .
                    "{$nameCode} = ob_get_clean();" . NL .
                    'echo(\'';
            }

            /**
             * Do replacement
             */
            $this->_replaceCode($code);
        }
    }

}
