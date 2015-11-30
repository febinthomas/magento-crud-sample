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
 * Admin search model
 *
 * @category    Febin
 * @package     Febin_Employee
 * @author      Febin Thomas
 */
class Febin_Employee_Model_Adminhtml_Search_Employee extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return Febin_Employee_Model_Adminhtml_Search_Employee
     * @author Febin Thomas
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('febin_employee/employee_collection')
            ->addAttributeToFilter('employee_name', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $employee) {
            $arr[] = array(
                'id'          => 'employee/1/'.$employee->getId(),
                'type'        => Mage::helper('febin_employee')->__('Employee'),
                'name'        => $employee->getEmployeeName(),
                'description' => $employee->getEmployeeName(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/employee_employee/edit',
                    array('id'=>$employee->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
