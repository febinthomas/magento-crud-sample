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
 * Adminhtml employee attribute edit page tabs
 *
 * @category    Febin
 * @package     Febin_Employee
 * @author      Febin Thomas
 */
class Febin_Employee_Block_Adminhtml_Employee_Attribute_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * constructor
     *
     * @access public
     * @author Febin Thomas
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('employee_attribute_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('febin_employee')->__('Attribute Information'));
    }

    /**
     * add attribute tabs
     *
     * @access protected
     * @return Febin_Employee_Adminhtml_Employee_Attribute_Edit_Tabs
     * @author Febin Thomas
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'main',
            array(
                'label'     => Mage::helper('febin_employee')->__('Properties'),
                'title'     => Mage::helper('febin_employee')->__('Properties'),
                'content'   => $this->getLayout()->createBlock(
                    'febin_employee/adminhtml_employee_attribute_edit_tab_main'
                )
                ->toHtml(),
                'active'    => true
            )
        );
        $this->addTab(
            'labels',
            array(
                'label'     => Mage::helper('febin_employee')->__('Manage Label / Options'),
                'title'     => Mage::helper('febin_employee')->__('Manage Label / Options'),
                'content'   => $this->getLayout()->createBlock(
                    'febin_employee/adminhtml_employee_attribute_edit_tab_options'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }
}
