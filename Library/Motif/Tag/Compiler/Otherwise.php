<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Compile <motif:otherwise>
 */
class Motif_Tag_Compiler_Otherwise extends Motif_Tag_Compiler_Abstract
{

    /**
     * @var string Tag's name
     */
    protected $_tagName = 'otherwise';

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
    }

    /**
     * Compile tag matches to native PHP code
     */
    protected function _compileMatches()
    {
        /**
         * Parse opening tags
         */
        $code = ''.
            'default:' . NL .
			'echo(\'';

        $this->_replaceCode($code, self::OPENING_TAGS);

        /**
         * Parse closing tags
         */
        $code = '' .
            '\');' . NL .
            'break;';

        $this->_replaceCode($code, self::CLOSING_TAGS);
    }

}
