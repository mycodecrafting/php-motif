<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Compile <motif:array:join var="varName" [ glue="&nbsp;" ] />
 */
class Motif_Tag_Compiler_Array_Join extends Motif_Tag_Compiler_Abstract
{

    /**
     * @var string Tag's name
     */
    protected $_tagName = 'array:join';

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
            'var'   => new Motif_Tag_Attribute_Required(self::MATCH_VAR),
            'glue'  => new Motif_Tag_Attribute(self::MATCH_WILDCARD, '&nbsp;'),
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
            $glue = $this->getAttribute('glue');

            $code = '' .
                '\');' . NL .
                "if (isset({$varCode}))" . NL .
                '{' . NL .
                    "echo implode('{$glue}', {$varCode});" . NL .
                '}' . NL .
                'echo(\'';

            /**
             * Do replacement
             */
            $this->_replaceCode($code);
        }
    }

}
