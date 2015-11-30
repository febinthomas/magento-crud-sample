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
 * Employee admin attribute block
 *
 * @category    Febin
 * @package     Febin_Employee
 * @author      Febin Thomas
 */
class Febin_Employee_Block_Adminhtml_Employee_Attribute extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * constructor
     *
     * @access public
     * @author Febin Thomas
     */
    public function __construct()
    {
        $this->_controller = 'adminhtml_employee_attribute';
        $this->_blockGroup = 'febin_employee';
        $this->_headerText = Mage::helper('febin_employee')->__('Manage Employee Attributes');
        parent::__construct();
        $this->_updateButton(
            'add',
            'label',
            Mage::helper('febin_employee')->__('Add New Employee Attribute')
        );
    }
}
