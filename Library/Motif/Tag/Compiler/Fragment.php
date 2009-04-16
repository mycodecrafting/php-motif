<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Compile <motif:fragment name="varName">
 */
class Motif_Tag_Compiler_Fragment extends Motif_Tag_Compiler_Abstract
{

    /**
     * @var string Tag's name
     */
    protected $_tagName = 'fragment';

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
            'name' => new Motif_Tag_Attribute_Required(self::MATCH_VAR),
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
            $nameCode = $this->_parseVarName($this->getAttribute('name'));

        	$code = '' .
        	    '\');' . NL .
                'ob_start();' . NL .
                "if (!isset({$nameCode}))" . NL .
                '{' . NL .
                    "{$nameCode} = array();" . NL .
                '}' . NL .
                "\$___frag =& {$nameCode};" . NL .
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
            '$___frag = ob_get_clean();' . NL .
            'unset($___frag);' . NL .
            'echo(\'';

        $this->_replaceCode($code, self::CLOSING_TAGS);
    }

}
