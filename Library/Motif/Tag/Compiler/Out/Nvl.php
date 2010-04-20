<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Compile <motif:out var="varName" />
 */
class Motif_Tag_Compiler_Out_Nvl extends Motif_Tag_Compiler_Abstract
{

    /**
     * @var string Tag's name
     */
    protected $_tagName = 'out:nvl';

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
            'var'       => new Motif_Tag_Attribute_Required(self::MATCH_VAR),
            'default'   => new Motif_Tag_Attribute_Required(self::MATCH_WILDCARD),
    		'isvar'     => new Motif_Tag_Attribute('isvar'),
        );
    }

    /**
     * Compile tag matches to native PHP code
     */
    protected function _compileMatches()
    {
        foreach ($this->_tagMatches as $match)
        {
            $varCode = $this->_parseVarName($this->getAttribute('var'));
            $default = $this->getAttribute('default');

            $code = '' .
                '\');' . NL .
                "if (isset({$varCode}) && (strval({$varCode}) != ''))" . NL .
                '{' . NL .
                    "echo {$varCode};" . NL .
                '}' . NL .
                'else' . NL .
                '{' . NL .
                    "echo '{$default}';" . NL .
                '}' . NL .
                'echo(\'';

            /**
             * Do replacement
             */
            $this->_replaceCode($code);
        }
    }

}
