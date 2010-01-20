<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Compile <motif:meta name="keywords|description" content="Put the content here" />
 */
class Motif_Tag_Compiler_Meta extends Motif_Tag_Compiler_Abstract
{

    /**
     * @var string Tag's name
     */
    protected $_tagName = 'meta';

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
            'name'      => new Motif_Tag_Attribute_Required('keywords|description'),
            'content'   => new Motif_Tag_Attribute_Required(self::MATCH_WILDCARD),
        );
    }

    /**
     * Compile tag matches to native PHP code
     */
    protected function _compileMatches()
    {
        foreach ($this->_tagMatches as $match)
        {
            $nameCode = $this->_parseVarName(sprintf('motif.meta.%s', $this->getAttribute('name')));
            $content = $this->getAttribute('content');

            $code = '' .
                '\');' . NL .
                'ob_start();' . NL .
                "echo('{$content}');" . NL .
                "{$nameCode} = ob_get_clean();" . NL .
                'echo(\'';

            /**
             * Do replacement
             */
            $this->_replaceCode($code);
        }
    }

}
