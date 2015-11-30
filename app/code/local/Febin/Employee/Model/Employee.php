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
 * Employee model
 *
 * @category    Febin
 * @package     Febin_Employee
 * @author      Febin Thomas
 */
class Febin_Employee_Model_Employee extends Mage_Catalog_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'febin_employee_employee';
    const CACHE_TAG = 'febin_employee_employee';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'febin_employee_employee';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'employee';

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Febin Thomas
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('febin_employee/employee');
    }

    /**
     * before save employee
     *
     * @access protected
     * @return Febin_Employee_Model_Employee
     * @author Febin Thomas
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }

    /**
     * get the employee Description
     *
     * @access public
     * @return string
     * @author Febin Thomas
     */
    public function getEmployeeDescription()
    {
        $employee_description = $this->getData('employee_description');
        $helper = Mage::helper('cms');
        $processor = $helper->getBlockTemplateProcessor();
        $html = $processor->filter($employee_description);
        return $html;
    }

    /**
     * save employee relation
     *
     * @access public
     * @return Febin_Employee_Model_Employee
     * @author Febin Thomas
     */
    protected function _afterSave()
    {
        return parent::_afterSave();
    }

    /**
     * Retrieve default attribute set id
     *
     * @access public
     * @return int
     * @author Febin Thomas
     */
    public function getDefaultAttributeSetId()
    {
        return $this->getResource()->getEntityType()->getDefaultAttributeSetId();
    }

    /**
     * get attribute text value
     *
     * @access public
     * @param $attributeCode
     * @return string
     * @author Febin Thomas
     */
    public function getAttributeText($attributeCode)
    {
        $text = $this->getResource()
            ->getAttribute($attributeCode)
            ->getSource()
            ->getOptionText($this->getData($attributeCode));
        if (is_array($text)) {
            return implode(', ', $text);
        }
        return $text;
    }

    /**
     * get default values
     *
     * @access public
     * @return array
     * @author Febin Thomas
     */
    public function getDefaultValues()
    {
        $values = array();
        $values['status'] = 1;
        return $values;
    }
    
}
