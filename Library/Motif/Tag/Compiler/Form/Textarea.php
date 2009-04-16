<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Compile <motif:form:textarea var="tplVar" />
 */
class Motif_Tag_Compiler_Form_Textarea extends Motif_Tag_Compiler_Abstract
{

    /**
     * @var string Tag's name
     */
    protected $_tagName = 'form:textarea';

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
            'rows'  => new Motif_Tag_Attribute(self::MATCH_WILDCARD, 10),
            'cols'  => new Motif_Tag_Attribute(self::MATCH_WILDCARD, 76),
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
            $cols = $this->getAttribute('cols');
            $rows = $this->getAttribute('rows');
            $inputAttrs = $this->_getAttributeString($exclude = 'var', 'value', 'cols', 'rows');

            /**
             * Do replacement
             */
            $this->_replaceCode(sprintf(
                '<motif:choose>' . NL .
                    '<motif:when var="%1$s">' . NL .
                        '<textarea name="%1$s" id="%1$s" cols="%3$d" rows="%4$d"%5$s>{%1$s}</textarea>' . NL .
                    '</motif:when>' . NL .
                    '<motif:otherwise>' .
                        '<textarea name="%1$s" id="%1$s" cols="%3$d" rows="%4$d"%5$s>%2$s</textarea>' . NL .
                    '</motif:otherwise>' . NL .
                '</motif:choose>',
                $inputName,
                $inputValue,
                $cols,
                $rows,
                $inputAttrs
            ));
        }
    }

}
