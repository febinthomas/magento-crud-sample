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
 * Employee admin edit tabs
 *
 * @category    Febin
 * @package     Febin_Employee
 * @author      Febin Thomas
 */
class Febin_Employee_Block_Adminhtml_Employee_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize Tabs
     *
     * @access public
     * @author Febin Thomas
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('employee_info_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('febin_employee')->__('Employee Information'));
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return Febin_Employee_Block_Adminhtml_Employee_Edit_Tabs
     * @author Febin Thomas
     */
    protected function _prepareLayout()
    {
        $employee = $this->getEmployee();
        $entity = Mage::getModel('eav/entity_type')
            ->load('febin_employee_employee', 'entity_type_code');
        $attributes = Mage::getResourceModel('eav/entity_attribute_collection')
                ->setEntityTypeFilter($entity->getEntityTypeId());
        $attributes->getSelect()->order('additional_table.position', 'ASC');

        $this->addTab(
            'info',
            array(
                'label'   => Mage::helper('febin_employee')->__('Employee Information'),
                'content' => $this->getLayout()->createBlock(
                    'febin_employee/adminhtml_employee_edit_tab_attributes'
                )
                ->setAttributes($attributes)
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve employee entity
     *
     * @access public
     * @return Febin_Employee_Model_Employee
     * @author Febin Thomas
     */
    public function getEmployee()
    {
        return Mage::registry('current_employee');
    }
}
