<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Compile <motif:number:format var="varName" [ decimals="0" decimalpoint="." thousands="," ] />
 */
class Motif_Tag_Compiler_Number_Format extends Motif_Tag_Compiler_Abstract
{

    /**
     * @var string Tag's name
     */
    protected $_tagName = 'number:format';

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
            'var'           => new Motif_Tag_Attribute_Required(self::MATCH_VAR),
            'decimals'      => new Motif_Tag_Attribute(self::MATCH_WILDCARD, '0'),
            'decimalpoint'  => new Motif_Tag_Attribute(self::MATCH_WILDCARD, '.'),
            'thousands'     => new Motif_Tag_Attribute(self::MATCH_WILDCARD, ','),
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
            $decimals = intval($this->getAttribute('decimals'));
            $decimalpoint = $this->getAttribute('decimalpoint');
            $thousands = $this->getAttribute('thousands');

            $code = '' .
                '\');' . NL .
                "if (isset({$varCode}))" . NL .
                '{' . NL .
                    "echo number_format({$varCode}, {$decimals}, '{$decimalpoint}', '{$thousands}');" . NL .
                '}' . NL .
                'echo(\'';

            /**
             * Do replacement
             */
            $this->_replaceCode($code);
        }
    }

}
