<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Compile <motif:title>Put the title here</motif:title>
 */
class Motif_Tag_Compiler_Title extends Motif_Tag_Compiler_Abstract
{

    /**
     * @var string Tag's name
     */
    protected $_tagName = 'title';

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
        $this->_attributes = array();
    }

    /**
     * Compile tag matches to native PHP code
     */
    protected function _compileMatches()
    {
        /**
         * Parse opening tags
         */
        $nameCode = $this->_parseVarName('motif.title');

        foreach ($this->_tagMatches as $match)
        {
        	$code = '' .
        	    '\');' . NL .
                'ob_start();' . NL .
                "if (!isset({$nameCode}))" . NL .
                '{' . NL .
                    "{$nameCode} = array();" . NL .
                '}' . NL .
                "\$___t =& {$nameCode};" . NL .
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
            '$___t = ob_get_clean();' . NL .
            'unset($___t);' . NL .
            'echo(\'';

        $this->_replaceCode($code, self::CLOSING_TAGS);
    }

}
