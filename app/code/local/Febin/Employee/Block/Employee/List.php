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
 * Employee list block
 *
 * @category    Febin
 * @package     Febin_Employee
 * @author Febin Thomas
 */
class Febin_Employee_Block_Employee_List extends Mage_Core_Block_Template
{
    /**
     * initialize
     *
     * @access public
     * @author Febin Thomas
     */
    public function _construct()
    {
        parent::_construct();
        $employees = Mage::getResourceModel('febin_employee/employee_collection')
                         ->setStoreId(Mage::app()->getStore()->getId())
                         ->addAttributeToSelect('*')
                         ->addAttributeToFilter('status', 1);
        $employees->setOrder('employee_name', 'asc');
        $this->setEmployees($employees);
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return Febin_Employee_Block_Employee_List
     * @author Febin Thomas
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock(
            'page/html_pager',
            'febin_employee.employee.html.pager'
        )
        ->setCollection($this->getEmployees());
        $this->setChild('pager', $pager);
        $this->getEmployees()->load();
        return $this;
    }

    /**
     * get the pager html
     *
     * @access public
     * @return string
     * @author Febin Thomas
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
}
