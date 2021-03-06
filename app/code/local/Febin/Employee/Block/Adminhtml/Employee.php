<?php
/**
 * Febin_Employee extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Febin
 * @package        Febin_Employee
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Employee admin block
 *
 * @category    Febin
 * @package     Febin_Employee
 * @author      Febin Thomas
 */
class Febin_Employee_Block_Adminhtml_Employee extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Febin Thomas
     */
    public function __construct()
    {
        $this->_controller         = 'adminhtml_employee';
        $this->_blockGroup         = 'febin_employee';
        parent::__construct();
        $this->_headerText         = Mage::helper('febin_employee')->__('Employee');
        $this->_updateButton('add', 'label', Mage::helper('febin_employee')->__('Add Employee'));

        $this->setTemplate('febin_employee/grid.phtml');
    }
}
