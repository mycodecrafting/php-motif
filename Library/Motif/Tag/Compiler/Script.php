<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Compile <motif:script src="relative/path/to/script.js" />
 */
class Motif_Tag_Compiler_Script extends Motif_Tag_Compiler_Abstract
{

    /**
     * @var string Tag's name
     */
    protected $_tagName = 'script';

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
            'src' => new Motif_Tag_Attribute_Required(self::MATCH_WILDCARD),
        );
    }

    /**
     * Compile tag matches to native PHP code
     */
    protected function _compileMatches()
    {
        foreach ($this->_tagMatches as $match)
        {
            $nameCode = $this->_parseVarName('motif.script');
            $src = $this->getAttribute('src');

            $code = '' .
                '\');' . NL .
                'ob_start();' . NL .
                "if (!isset({$nameCode}))" . NL .
                '{' . NL .
                    "{$nameCode} = '';" . NL .
                '}' . NL .
                "include(\$___engine->getTemplate()->includeTemplate('$src'));" . NL .
                "{$nameCode} .= ob_get_clean() . NL;" . NL .
                'echo(\'';

            /**
             * Do replacement
             */
            $this->_replaceCode($code);
        }
    }

}
