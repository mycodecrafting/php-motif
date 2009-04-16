<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Motif_Tag_Attribute
{

	protected $_pattern;
	protected $_default;

	public function __construct($pattern, $default = false)
	{
		$this->_pattern = $pattern;
		$this->_default = $default;
	}

    public function getPattern()
    {
        return $this->_pattern;
    }

    public function getDefault()
    {
        return $this->_default;
    }

}
