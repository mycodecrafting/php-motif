<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Motif_Tag_Matches_Iterator implements Iterator
{

    /**
     * @var array Tag matches
     */
    protected $_matches = array();

    /**
     * @var Motif_Tag_Compiler_Abstract Tag compiler
     */
    protected $_compiler;

    /**
     * @var integer Current matches index
     */
    protected $_index = 0;

    /**
     * Constructor
     *
     * @param array $matches Tag matches
     * @param Motif_Tag_Compiler_Abstract $compiler Tag compiler
     */
    public function __construct(array $matches, Motif_Tag_Compiler_Abstract $compiler)
    {
        $this->_matches = $matches;
        $this->_compiler = $compiler;
    }

    /**
     * Get current match
     */
    public function current()
    {
        $this->_compiler->setMatch($this->_matches[$this->_index]);
        $this->_compiler->checkAttributes();
        return $this->_matches[$this->_index];
    }

    public function key()
    {
        return $this->_index;
    }

    public function next()
    {
        ++$this->_index;
    }

    public function rewind()
    {
        $this->_index = 0;
    }

    public function valid()
    {
        if (isset($this->_matches[$this->_index]))
        {
            return true;
        }

        return false;
    }

    public function count()
    {
        return count($this->_matches);
    }

}
