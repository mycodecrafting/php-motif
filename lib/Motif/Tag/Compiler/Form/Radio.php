<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Compile <motif:form:radio var="tplVar" value="1" [checked="checked" id="custom-id"] />
 */
class Motif_Tag_Compiler_Form_Radio extends Motif_Tag_Compiler_Abstract
{

    /**
     * @var string Tag's name
     */
    protected $_tagName = 'form:radio';

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
            'var' => new Motif_Tag_Attribute_Required(self::MATCH_VAR),
            'value' => new Motif_Tag_Attribute_Required(self::MATCH_WILDCARD),
            'id'    => new Motif_Tag_Attribute(self::MATCH_WILDCARD),
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
            $inputId = $this->getAttribute('id');
            $inputValue = $this->getAttribute('value');
            $inputAttrs = $this->_getAttributeString($exclude = 'var', 'value', 'id');
            $inputAttrsNotChecked = $this->_getAttributeString($exclude = 'var', 'value', 'id', 'checked');

            /**
             * Do replacement
             */
            $this->_replaceCode(sprintf(
                '<motif:choose>' . NL .
                    '<motif:when var="%1$s" value="%3$s">' . NL .
                        '<input type="radio" name="%1$s" id="%2$s" value="%3$s"%5$s checked="checked" />' . NL .
                    '</motif:when>' . NL .
                    '<motif:otherwise>' . NL .
                        '<input type="radio" name="%1$s" id="%2$s" value="%3$s"%4$s />' . NL .
                    '</motif:otherwise>' . NL .
                '</motif:choose>',
                $inputName,
                ($inputId ? $inputId : $inputName . '-' . $inputValue),
                $inputValue,
                $inputAttrs,
                $inputAttrsNotChecked
            ));
        }
    }

}
