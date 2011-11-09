<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Compile <motif:form var="formName">
 */
class Motif_Tag_Compiler_Form extends Motif_Tag_Compiler_Abstract
{

    /**
     * @var string Tag's name
     */
    protected $_tagName = 'form';

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
            $formName = $this->getAttribute('var');
            $varCode = $this->_parseVarName($formName);

            $code = '' .
                '\');' . NL .
                "if (isset({$varCode}))" . NL .
                '{' . NL .
                    "\$action = {$varCode}['action'];" . NL .
                    '$method = \'get\';' . NL .
                    "if (!isset({$varCode}['method']))" . NL .
                    '{' . NL .
                        "{$varCode}['method'] = 'get';" . NL .
                    '}' . NL .
                    "echo('<form name=\"{$formName}\" id=\"{$formName}\" action=\"{{$formName}.action}\" method=\"{{$formName}.method}\">');" . NL .
                '}' . NL .
                'echo(\'';

            /**
             * Do replacement
             */
            $this->_replaceCode($code);
        }

        /**
         * Parse closing tags
         */
        $this->_replaceCode('</form>', self::CLOSING_TAGS);
    }

}
