<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Compile <motif:form:text var="dealTitle" />
 */
class Motif_Tag_Compiler_Form_Text extends Motif_Tag_Compiler_Abstract
{

    /**
     * @var string Tag's name
     */
    protected $_tagName = 'form:text';

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

            /**
             * Do replacement
             */
            $this->_replaceCode(sprintf(
                '<motif:choose>' . NL .
                    '<motif:when var="%1$s">' . NL .
                        '<input type="text" name="%1$s" id="%2$s" value="{%1$s}"%4$s />' . NL .
                    '</motif:when>' . NL .
                    '<motif:otherwise>' .
                        '<input type="text" name="%1$s" id="%2$s" value="%3$s"%4$s />' . NL .
                    '</motif:otherwise>' . NL .
                '</motif:choose>',
                $inputName,
                ($inputId ? $inputId : $inputName),
                $inputValue,
                $inputAttrs
            ));
        }
    }

}
