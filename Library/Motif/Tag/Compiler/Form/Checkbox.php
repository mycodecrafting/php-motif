<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Compile <motif:form:checkbox var="tplVar" value="1" [checked="checked"] />
 */
class Motif_Tag_Compiler_Form_Checkbox extends Motif_Tag_Compiler_Abstract
{

    /**
     * @var string Tag's name
     */
    protected $_tagName = 'form:checkbox';

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
            $inputAttrsNotChecked = $this->_getAttributeString($exclude = 'var', 'value', 'checked');

            /**
             * Do replacement
             */
            $this->_replaceCode(sprintf(
                '<motif:choose>' . NL .
                    '<motif:when var="%1$s" value="%2$s">' . NL .
                        '<input type="checkbox" name="%1$s" id="%1$s" value="%2$s"%4$s checked="checked" />' . NL .
                    '</motif:when>' . NL .
                    '<motif:otherwise>' . NL .
                        '<input type="checkbox" name="%1$s" id="%1$s" value="%2$s"%3$s />' . NL .
                    '</motif:otherwise>' . NL .
                '</motif:choose>',
                $inputName,
                $inputValue,
                $inputAttrs,
                $inputAttrsNotChecked
            ));
        }
    }

}
