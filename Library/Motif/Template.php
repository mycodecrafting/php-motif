<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Motif_Template
{

    protected $_useCache = true;

    protected $_vars = array();

    protected $_templateFile;

    public function __construct($useCache = true)
    {
        $this->_useCache = $useCache;
    }

    protected static $_compilationDir;

    /**
     * Set the directory where compiled templates are cached to
     */
	public static function setCompilationDir($dir)
	{
		if (@is_dir($dir) === false)
		{
			throw new Motif_Template_Exception(sprintf(
				'Motif template compilation directory does not exist. Expected to find "%s"', $dir
			));
		}

		if (@is_writable($dir) === false)
		{
			throw new Motif_Template_Exception(sprintf(
				'Motif template compilation directory is not writable. Expected to be able to write to "%s"', $dir
			));
		}

        self::$_compilationDir = rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
	}

    /**
     * Get the template file
     *
     */
    public function getTemplate()
    {
        return $this->_templateFile;
    }

    /**
     * Set the template file
     *
     * @param mixed $file The template file
     * @return void
     */
	public function setTemplate($file)
	{
        $this->_templateFile = $file;
	}

    /**
     * Check the template exists
     *
     * @return boolean
     */
    public function templateExists()
    {
        return file_exists($this->_templateFile);
    }

    /**
     * Get a set template var
     *
     * @return mixed
     */
    public function getVar($name)
    {
        if (isset($this->_vars[$name]))
        {
            return $this->_vars[$name];
        }

        return false;
    }

    /**
     * Get all template vars
     *
     * @return array
     */
    public function getVars()
    {
        return $this->_vars;
    }

    /**
     * Set a template var to given value
     *
     * @param string $name Template var name
     * @param mixed $value Template var value
     * @return void
     */
    public function setVar($name, $value = null)
    {
        $this->_vars[$name] = $value;
    }

    /**
     * Set all template vars
     *
     * @param array $vars Template vars
     * @return void
     */
    public function setVars(array $vars)
    {
        $this->_vars = $vars;
    }

    /**
     * Include an inner template in this template
     */
    public function includeTemplate($innerTemplate)
    {
		$innerTemplate = dirname($this->_templateFile) . DIRECTORY_SEPARATOR . $innerTemplate;

        /**
         * Inner template files does not exist
         */
        if (file_exists($innerTemplate) === false)
        {
            throw new Motif_Template_Exception(sprintf(
                'Motif include template does not exist. Expected to find "%s"', $innerTemplate
            ));
        }

        $template = new self($this->_useCache);

        $template->setTemplate($innerTemplate);

        return $template->_check();
    }

    /**
     * Build out the template
     */
    public function build()
    {
        $this->_check();
        return $this->_parse();
    }

    /**
     * Check if a template needs to be re-compiled, and do so if necessary
     */
    protected function _check()
    {
        if (
            (file_exists($this->_file()) === false) ||
            (file_exists($this->_templateFile) === false) ||
            (filemtime($this->_templateFile) > filemtime($this->_file())) ||
            ($this->_useCache === false)
        )
        {
            $this->_compile();
        }

        return $this->_file();
    }

    /**
     * Parse template with input vars
     */
    protected function _parse()
    {
        $engine = new Motif_Engine($this);

        $engine->importVars($this->getVars());

		$parsed = $engine->parse($this->_file());

		$this->setVars($engine->exportVars());

		return $parsed;
    }

    /**
     * Compile template to native PHP
     */
    protected function _compile()
    {
		$engine = new Motif_Engine($this);

		$stripSpace = true;

		if (substr($this->_templateFile, -4) === '.txt')
		{
			$stripSpace = false;
		}

		$contents = $engine->compile(file_get_contents($this->_templateFile), $stripSpace);

		// create temp file
		$tmpFile = tempnam('/tmp', 'MOTIF');
		file_put_contents($tmpFile, $contents);

		// strip php whitespace
//		$contents = php_strip_whitespace($tmpFile);

		// write final template
		file_put_contents($this->_file(), $contents);
		chmod($this->_file(), 0777);

		// remove temp file
        @unlink($tmpFile);
    }

	protected function _file()
	{
		return self::$_compilationDir . md5($this->_templateFile) . '.php';
	}

}
