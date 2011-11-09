<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Compile <motif:when ... >
 */
class Motif_Tag_Compiler_When extends Motif_Tag_Compiler_Abstract
{

    /**
     * @var string Tag's name
     */
    protected $_tagName = 'when';

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
    	$this->_attributes = array(
    		'var'       => new Motif_Tag_Attribute_Required(self::MATCH_VAR),
    		'value'     => new Motif_Tag_Attribute(self::MATCH_WILDCARD),
    		'condition' => new Motif_Tag_Attribute(self::MATCH_CONDITION),
    		'isvar'     => new Motif_Tag_Attribute('isvar'),
    	);
    }

    /**
     * Compile tag matches to native PHP code
     */
    protected function _compileMatches()
    {
        foreach ($this->_tagMatches as $match)
        {
            $varCode = $this->_parseVarName($this->getAttribute('var'));
    		$condition = $this->_parseCondition();

            /**
             * Parse <motif:when var="tplVar" [condition="gte" isvar="isvar"] value="somevalue">
             */
            if (($value = $this->getAttribute('value')) !== false)
            {
                if (in_array($condition, array('in', 'notin')))
                {
                    $preOp = '';
                    $op = '&&';

                    if ($condition === 'notin')
                    {
                        $preOp = '!';
                        $op = '||';
                    }

                    $valueCode = $this->_parseVarName($value);

                    $code = NL .
                        "case ({$preOp}isset({$valueCode}) {$op} {$preOp}in_array({$varCode}, {$valueCode})):" . NL .
                            'echo(\'';
                }
                else
                {
                    $preOp = '';
                    $op = '&&';

                    if ($condition === '!=')
                    {
                        $preOp = '!';
                        $op = '||';
                    }

                    /**
                     * value: true, false, numeric
                     */
                    if (preg_match('/^(true|false|([0-9]+))$/', strtolower($value)))
                    {
                        $value = strtolower($value);
                    }

                    /**
                     * value: isvar
                     */
                    elseif ($this->getAttribute('isvar') && $this->_isVarMatch($value))
                    {
                        $value = $this->_parseVarName($value);

                        $op = sprintf('&& isset(%s) &&', $value);

                        if ($condition === '!=')
                        {
                            $op = sprintf('|| !isset(%s) ||', $value);
                        }
                    }

                    /**
                     * value: string
                     */
                    else
                    {
                        $value = sprintf('\'%s\'', $value);
                    }

                    $code = NL .
                        "case ({$preOp}isset({$varCode}) $op (strval({$varCode}) $condition $value)):" . NL .
                            'echo(\'';
                }
            }

            /**
             * Parse <motif:when var="tplVar" [condition="notexists"]>
             */
            else
            {
                $preOp = '';
                $preOp2 = '!';
                $op = '&&';

                if ($condition === 'notexists')
                {
                    $preOp = '!';
                    $preOp2 = '';
                    $op = '||';
                }

                $code = NL .
                    "case ({$preOp}isset({$varCode}) $op {$preOp2}empty({$varCode})):" . NL .
                        'echo(\'';
            }

            /**
             * Do replacement
             */
            $this->_replaceCode($code);
        }

        /**
         * Parse closing tags
         */
        $code = '' .
            '\');' . NL .
            'break;';

        $this->_replaceCode($code, self::CLOSING_TAGS);
    }

}
