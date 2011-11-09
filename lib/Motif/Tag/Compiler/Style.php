<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Compile <motif:style src="relative/path/to/styles.css" />
 */
class Motif_Tag_Compiler_Style extends Motif_Tag_Compiler_Abstract
{

    /**
     * @var string Tag's name
     */
    protected $_tagName = 'style';

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
            $nameCode = $this->_parseVarName('motif.style');
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
