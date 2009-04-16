<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Motif_Engine
{

    /**
     * @var array template vars
     */
    protected $_vars = array();

    /**
     * Constructor
     */
    public function __construct(Motif_Template $template = null)
    {
        $this->_template = $template;
    }

    protected $_template = null;

    public function getTemplate()
    {
        return $this->_template;
    }

    /**
     * Import vars
     *
     * @param array $vars
     * @return void
     */
    public function importVars(array $vars)
    {
        $this->_vars = array_merge($this->_vars, $vars);
    }

    /**
     * Export vars
     *
     * @return array Template vars
     */
    public function exportVars()
    {
        return $this->_vars;
    }

    /**
     * @var array Registered custom tag compilers
     */
    protected static $_registered = array();

    /**
     * Register a custom tag compilation handler
     *
     * @param string $tag
     * @param string $compiler
     */
    public static function register($tag, $compiler)
    {
        self::$_registered[$tag] = $compiler;
    }

    /**
     * Parse an already compiled template
     */
    public function parse($compiledCode)
    {
        /**
         * Set engine instance to local variable, for compiled template usage
         */
        $___engine = $this;

        /**
         * Export template vars to local variable, for compiled template usage
         */
        $___vars = $this->exportVars();

        /**
         * The stack and stack count allow access to template vars outside the scope of a block
         * to be used within the block.  Each block level increases and adds to the stack count,
         * allowing for an infinite number of block nesting levels.  This allows facilities
         * such as:
         *     parent.varName, parent.parent.varName, top.varName, etc
         * to work from within a block in order to use vars outside of the block's scope.
         */
        $___stackCount = 0;
        $___stack[$___stackCount++] = $___vars;

        /**
         * Run & capture the output of compiled template
         */
        ob_start();
        include $compiledCode;
        $parsed = ob_get_clean();

        /**
         * Import any new/changed template vars
         */
        $this->importVars($___vars);

        /**
         * Return parsed output.
         */
        return $parsed;
    }

    /**
     * Compile template to native PHP code
     */
    public function compile($code, $stripSpace = false)
    {
        if ($stripSpace === true)
        {
            $code = preg_replace('/(\s*)([\r\n]+)/', NL, $code);
        }

        $code = 'echo(\'' . trim(str_replace("'", "\'", $code)) . '\');';

        /**
         * Strip comments
         */
        $code = $this->_stripComments($code);

        /**
         * Compile Motif tags
         */
        $code = $this->_compileTags($code);

        /**
         * Transform shorthand vars
         */
        $code = $this->_transformVars($code);

        /**
         * Re-compile Motif tags to catch transformed vars
         */
        $code = $this->_compileTags($code);

        if ($stripSpace === true)
        {
            $code = $this->_stripSpace($code);
        }

        return '<?php' . NL . $code;
    }

    /**
     * Strip motif comments from code
     *
     * @param string $code Code to do transformation on
     * @return string Transformed code
     */
    protected function _stripComments($code)
    {
        return preg_replace('/(\s*)<!--(\s*)comment(.*)-->/Uis', '', $code);
    }

    /**
     * Compile motif tags
     */
    protected function _compileTags($code)
    {
        if (!preg_match('/<motif:([a-z0-9:]+)(\s+.+)?>/U', $code, $matches))
        {
            return $code;
        }

        /**
         * Do until all tags are compiled; thus allowing tags to use other tags
         */
        do
        {
            $tagName = $matches[1];

            /**
             * Get all matches for this tag
             */
            preg_match_all('/<motif:' . $tagName . '(\s+.+)?>/U', $code, $matches, PREG_PATTERN_ORDER);
            $tagMatches = $matches[0];

            /**
             * This is a registered tag
             */
            if (array_key_exists($tagName, self::$_registered) === true)
            {
                $class = self::$_registered[$tag];
            }

            /**
             * Autodiscovering the class
             */
            else
            {
                $class = 'Motif_Tag_Compiler_' . implode('_', array_map('ucfirst', explode(':', $tagName)));
            }

            /**
             * Catch invalid tag names and/or missing tag compilers
             */
            if (class_exists($class) === false)
            {
                $this->_throwCompilationError($tagName, 'Unknown tag');
            }

            $compiler = new $class($tagMatches);

            $code = $compiler->compile($code);
        }
        while (preg_match('/<motif:([a-z0-9:]+)(\s+.+)?>/U', $code, $matches));

        return $code;
    }

    /**
     * Transform shorthand {varName} to <motif:out var="varName" /> tags
     *
     * @param string $code Code to do transformation on
     * @return string Transformed code
     */
    protected function _transformVars($code)
    {
        /**
         * Nothing to transform
         */
        if (!preg_match_all('/{([A-z0-9_.]+)}/', $code, $vars))
        {
            return $code;
        }

        /**
         * Replace each ocurrance found
         */
        foreach ($vars[1] as $var)
        {
            $code = str_replace(
                sprintf('{%s}', $var),
                sprintf('<motif:out var="%s" />', $var),
                $code
            );
        }

        return $code;
    }

    /**
     * Strip extraneous whitespace from code
     *
     * @param string $code Code to do transformation on
     * @return string Transformed code
     */
    protected function _stripSpace($code)
    {
        $code = preg_replace('/echo\(\'(\s*)\'\);/s', '', $code);
        $code = preg_replace('/echo\(\'([\r\n]+)(.*)\'\);/Us', 'echo(\'$2\');', $code);
        $code = preg_replace('/echo\(\'(.*)([\r\n]+)([\s]+)\'\);/Us', 'echo(\'$1' . "\n" . '\');', $code);

        return $code;
    }

    /**
     * ...
     */
    protected function _throwCompilationError($tag, $message)
    {
        throw new Motif_Tag_Compiler_Exception(sprintf(
            '<strong>Motif compilation error:</strong> <em>&lt;motif:%s&gt;:</em> %s', $tag, $message
        ));
    }

}
