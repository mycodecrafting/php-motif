<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Compile <motif:form:password var="passwordVar" />
 */
class Motif_Tag_Compiler_Form_Password extends Motif_Tag_Compiler_Abstract
{

    /**
     * @var string Tag's name
     */
    protected $_tagName = 'form:password';

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
            'value' => new Motif_Tag_Attribute(self::MATCH_WILDCARD),
        );
    }

    /**
     * Compile tag matches to native PHP code
     */
    protected function _compileMatches()
    {
        foreach ($this->_tagMatches as $match)
        {
            $inputName = $this->getAttribute('var');
            $inputValue = $this->getAttribute('value');
            $inputAttrs = $this->_getAttributeString($exclude = 'var', 'value');

            /**
             * Do replacement
             */
            $this->_replaceCode(sprintf(
                '<motif:choose>' . NL .
                    '<motif:when var="%1$s">' . NL .
                        '<input type="password" name="%1$s" id="%1$s" value="{%1$s}"%3$s />' . NL .
                    '</motif:when>' . NL .
                    '<motif:otherwise>' .
                        '<input type="password" name="%1$s" id="%1$s" value="%2$s"%3$s />' . NL .
                    '</motif:otherwise>' . NL .
                '</motif:choose>',
                $inputName,
                $inputValue,
                $inputAttrs
            ));
        }
    }

}
