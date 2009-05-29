<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Compile <motif:form:select var="varName" options="optsVarName" />
 */
class Motif_Tag_Compiler_Form_Select extends Motif_Tag_Compiler_Abstract
{

    /**
     * @var string Tag's name
     */
    protected $_tagName = 'form:select';

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
            'var'       => new Motif_Tag_Attribute_Required(self::MATCH_VAR),
            'options'   => new Motif_Tag_Attribute_Required(self::MATCH_VAR),
            'id'        => new Motif_Tag_Attribute(self::MATCH_WILDCARD),
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
            $options = $this->getAttribute('options');
            $inputId = $this->getAttribute('id');
            $inputAttrs = $this->_getAttributeString($exclude = 'var', 'options', 'id');

            $code = sprintf(
                '<select name="%1$s" id="%2$s"%3$s>' . NL .
                    '<motif:block var="%4$s">' . NL .
                        '<motif:choose>' . NL .
                            '<motif:when var="parent.%1$s" value="item.value" isvar="isvar">' . NL .
                                '<option value="{item.value}" selected="selected">{item.name}</option>' . NL .
                            '</motif:when>' . NL .
                            '<motif:otherwise>' . NL .
                                '<option value="{item.value}">{item.name}</option>' . NL .
                            '</motif:otherwise>' . NL .
                        '</motif:choose>' . NL .
                    '</motif:block>' . NL .
                '</select>',
                $inputName,
                ($inputId ? $inputId : $inputName),
                $inputAttrs,
                $options
            );

            /**
             * Do replacement
             */
            $this->_replaceCode($code);
        }
    }

}
