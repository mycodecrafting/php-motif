<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Compile <motif:block var="varName">
 */
class Motif_Tag_Compiler_Block_Count extends Motif_Tag_Compiler_Abstract
{

    /**
     * @var string Tag's name
     */
    protected $_tagName = 'block:count';

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
            'from'  => new Motif_Tag_Attribute_Required(self::MATCH_WILDCARD),
            'to'    => new Motif_Tag_Attribute_Required(self::MATCH_WILDCARD),
            'step'  => new Motif_Tag_Attribute(self::MATCH_WILDCARD, 1),
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
            $nameCode = $this->_parseVarName('motif.counter');
            $countFrom = $this->_parseVarName($this->getAttribute('from'));
            $countTo = $this->_parseVarName($this->getAttribute('to'));
            $step = $this->getAttribute('step');

            $code = '' .
                '\');' . NL .
                "{$nameCode} = array();" . NL .
                "for (\$c = {$countFrom}; \$c <= {$countTo}; \$c += {$step})" . NL .
                '{' . NL .
                    "{$nameCode}[]['count'] = \$c;" . NL .
                '}' . NL .
                'echo(\'' .
                    '<motif:block var="motif.counter">';

            /**
             * Do replacement
             */
            $this->_replaceCode($code);
        }

        /**
         * Parse closing tags
         */
        $this->_replaceCode('</motif:block>', self::CLOSING_TAGS);
    }

}
