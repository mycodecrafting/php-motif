<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * Compile <motif:block var="varName">
 */
class Motif_Tag_Compiler_Block extends Motif_Tag_Compiler_Abstract
{

    /**
     * @var string Tag's name
     */
    protected $_tagName = 'block';

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
            'var' => new Motif_Tag_Attribute_Required(self::MATCH_VAR),
        );
    }

    /**
     * Compile tag matches to native PHP code
     */
    protected function _compileMatches()
    {
        /**
         * Parse opening tags
         */
        foreach ($this->_tagMatches as $match)
        {
            $varCode = $this->_parseVarName($this->getAttribute('var'));

            $code = '' .
                '\');' . NL .
                "if (isset({$varCode}) && !empty({$varCode}))" . NL .
                '{' . NL .
                    "\$___array = $varCode;" . NL .
                    'if (!is_array($___array))' . NL .
                    '{' . NL .
                        '$___array = array($___array);' . NL .
                    '}' . NL .
                    '$___stack[$___stackCount++] = $___vars;' . NL .
                    '$___arraySize = count($___array);' . NL .
                    'foreach ($___array as $___cnt => $___row)' . NL .
                    '{' . NL .
                        'if ($___cnt === 0)' . NL .
                        '{' . NL .
                            '$___row[\'block\'][\'isFirst\'] = \'true\';' . NL .
                        '}' . NL .
                        'if (($___cnt + 1) === $___arraySize)' . NL .
                        '{' . NL .
                            '$___row[\'block\'][\'isLast\'] = \'true\';' . NL .
                        '}' . NL .
                        '$___row[\'block\'][\'count\'] = $___arraySize;' . NL .
                        '$___row[\'block\'][\'rowCount\'] = $___cnt + 1;' . NL .
                		'$___row[\'block\'][\'altRow\'] = $___cnt % 2;' . NL .
                		'$___row[\'block\'][\'oddEven\'] = (($___cnt % 2) ? \'odd\' : \'even\');' . NL .
                		'$___row[\'block\'][\'rowBit\'] = $___cnt % 2;' . NL .
                		'$___row[\'block\'][\'rowBit2\'] = ($___cnt % 2) + 1;' . NL .
                		'$___row[\'block\'][\'rowBit3\'] = ($___cnt % 3) + 1;' . NL .
                		'$___row[\'block\'][\'rowBit4\'] = ($___cnt % 4) + 1;' . NL .
                		'$___row[\'block\'][\'rowBit5\'] = ($___cnt % 5) + 1;' . NL .
                		'$___row[\'block\'][\'rowBit6\'] = ($___cnt % 6) + 1;' . NL .
                		'$___row[\'block\'][\'rowBit7\'] = ($___cnt % 7) + 1;' . NL .
                		'$___row[\'block\'][\'rowBit8\'] = ($___cnt % 8) + 1;' . NL .
                		'$___row[\'block\'][\'rowBit9\'] = ($___cnt % 8) + 1;' . NL .
                		'$___row[\'block\'][\'rowBit10\'] = ($___cnt % 10) + 1;' . NL .
                		'$___item = $___row;' . NL .
                		'$___row[\'item\'] = $___item;' . NL .
                		'unset($___item);' . NL .
                        '$___vars = $___row;' . NL .
                        'echo(\'';

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
     	        '}' . NL .
     	        '$___vars = $___stack[--$___stackCount];' . NL .
     	    '}' . NL .
     	    'echo(\'';

        $this->_replaceCode($code, self::CLOSING_TAGS);
    }

}
