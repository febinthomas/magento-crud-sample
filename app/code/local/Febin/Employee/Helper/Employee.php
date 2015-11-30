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
 * Employee helper
 *
 * @category    Febin
 * @package     Febin_Employee
 * @author      Febin Thomas
 */
class Febin_Employee_Helper_Employee extends Mage_Core_Helper_Abstract
{

    /**
     * get the url to the employee list page
     *
     * @access public
     * @return string
     * @author Febin Thomas
     */
    public function getEmployeesUrl()
    {
        if ($listKey = Mage::getStoreConfig('febin_employee/employee/url_rewrite_list')) {
            return Mage::getUrl('', array('_direct'=>$listKey));
        }
        return Mage::getUrl('febin_employee/employee/index');
    }

    /**
     * check if breadcrumbs can be used
     *
     * @access public
     * @return bool
     * @author Febin Thomas
     */
    public function getUseBreadcrumbs()
    {
        return Mage::getStoreConfigFlag('febin_employee/employee/breadcrumbs');
    }

    /**
     * get base files dir
     *
     * @access public
     * @return string
     * @author Febin Thomas
     */
    public function getFileBaseDir()
    {
        return Mage::getBaseDir('media').DS.'employee'.DS.'file';
    }

    /**
     * get base file url
     *
     * @access public
     * @return string
     * @author Febin Thomas
     */
    public function getFileBaseUrl()
    {
        return Mage::getBaseUrl('media').'employee'.'/'.'file';
    }

    /**
     * get employee attribute source model
     *
     * @access public
     * @param string $inputType
     * @return mixed (string|null)
     * @author Febin Thomas
     */
     public function getAttributeSourceModelByInputType($inputType)
     {
         $inputTypes = $this->getAttributeInputTypes();
         if (!empty($inputTypes[$inputType]['source_model'])) {
             return $inputTypes[$inputType]['source_model'];
         }
         return null;
     }

    /**
     * get attribute input types
     *
     * @access public
     * @param string $inputType
     * @return array()
     * @author Febin Thomas
     */
    public function getAttributeInputTypes($inputType = null)
    {
        $inputTypes = array(
            'multiselect' => array(
                'backend_model' => 'eav/entity_attribute_backend_array'
            ),
            'boolean'     => array(
                'source_model'  => 'eav/entity_attribute_source_boolean'
            ),
            'file'          => array(
                'backend_model' => 'febin_employee/employee_attribute_backend_file'
            ),
            'image'          => array(
                'backend_model' => 'febin_employee/employee_attribute_backend_image'
            ),
        );

        if (is_null($inputType)) {
            return $inputTypes;
        } else if (isset($inputTypes[$inputType])) {
            return $inputTypes[$inputType];
        }
        return array();
    }

    /**
     * get employee attribute backend model
     *
     * @access public
     * @param string $inputType
     * @return mixed (string|null)
     * @author Febin Thomas
     */
    public function getAttributeBackendModelByInputType($inputType)
    {
        $inputTypes = $this->getAttributeInputTypes();
        if (!empty($inputTypes[$inputType]['backend_model'])) {
            return $inputTypes[$inputType]['backend_model'];
        }
        return null;
    }

    /**
     * filter attribute content
     *
     * @access public
     * @param Febin_Employee_Model_Employee $employee
     * @param string $attributeHtml
     * @param string @attributeName
     * @return string
     * @author Febin Thomas
     */
    public function employeeAttribute($employee, $attributeHtml, $attributeName)
    {
        $attribute = Mage::getSingleton('eav/config')->getAttribute(
            Febin_Employee_Model_Employee::ENTITY,
            $attributeName
        );
        if ($attribute && $attribute->getId() && !$attribute->getIsWysiwygEnabled()) {
            if ($attribute->getFrontendInput() == 'textarea') {
                $attributeHtml = nl2br($attributeHtml);
            }
        }
        if ($attribute->getIsWysiwygEnabled()) {
            $attributeHtml = $this->_getTemplateProcessor()->filter($attributeHtml);
        }
        return $attributeHtml;
    }

    /**
     * get the template processor
     *
     * @access protected
     * @return Mage_Catalog_Model_Template_Filter
     * @author Febin Thomas
     */
    protected function _getTemplateProcessor()
    {
        if (null === $this->_templateProcessor) {
            $this->_templateProcessor = Mage::helper('catalog')->getPageTemplateProcessor();
        }
        return $this->_templateProcessor;
    }
}
