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
 * Employee admin controller
 *
 * @category    Febin
 * @package     Febin_Employee
 * @author      Febin Thomas
 */
class Febin_Employee_Adminhtml_Employee_EmployeeController extends Mage_Adminhtml_Controller_Action
{
    /**
     * constructor - set the used module name
     *
     * @access protected
     * @return void
     * @see Mage_Core_Controller_Varien_Action::_construct()
     * @author Febin Thomas
     */
    protected function _construct()
    {
        $this->setUsedModuleName('Febin_Employee');
    }

    /**
     * init the employee
     *
     * @access protected 
     * @return Febin_Employee_Model_Employee
     * @author Febin Thomas
     */
    protected function _initEmployee()
    {
        $this->_title($this->__('Febin'))
             ->_title($this->__('Manage Employee'));

        $employeeId  = (int) $this->getRequest()->getParam('id');
        $employee    = Mage::getModel('febin_employee/employee')
            ->setStoreId($this->getRequest()->getParam('store', 0));

        if ($employeeId) {
            $employee->load($employeeId);
        }
        Mage::register('current_employee', $employee);
        return $employee;
    }

    /**
     * default action for employee controller
     *
     * @access public
     * @return void
     * @author Febin Thomas
     */
    public function indexAction()
    {
        $this->_title($this->__('Febin'))
             ->_title($this->__('Manage Employee'));
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * new employee action
     *
     * @access public
     * @return void
     * @author Febin Thomas
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * edit employee action
     *
     * @access public
     * @return void
     * @author Febin Thomas
     */
    public function editAction()
    {
        $employeeId  = (int) $this->getRequest()->getParam('id');
        $employee    = $this->_initEmployee();
        if ($employeeId && !$employee->getId()) {
            $this->_getSession()->addError(
                Mage::helper('febin_employee')->__('This employee no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        if ($data = Mage::getSingleton('adminhtml/session')->getEmployeeData(true)) {
            $employee->setData($data);
        }
        $this->_title($employee->getEmployeeName());
        Mage::dispatchEvent(
            'febin_employee_employee_edit_action',
            array('employee' => $employee)
        );
        $this->loadLayout();
        if ($employee->getId()) {
            if (!Mage::app()->isSingleStoreMode() && ($switchBlock = $this->getLayout()->getBlock('store_switcher'))) {
                $switchBlock->setDefaultStoreName(Mage::helper('febin_employee')->__('Default Values'))
                    ->setWebsiteIds($employee->getWebsiteIds())
                    ->setSwitchUrl(
                        $this->getUrl(
                            '*/*/*',
                            array(
                                '_current'=>true,
                                'active_tab'=>null,
                                'tab' => null,
                                'store'=>null
                            )
                        )
                    );
            }
        } else {
            $this->getLayout()->getBlock('left')->unsetChild('store_switcher');
        }
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->renderLayout();
    }

    /**
     * save employee action
     *
     * @access public
     * @return void
     * @author Febin Thomas
     */
    public function saveAction()
    {
        $storeId        = $this->getRequest()->getParam('store');
        $redirectBack   = $this->getRequest()->getParam('back', false);
        $employeeId   = $this->getRequest()->getParam('id');
        $isEdit         = (int)($this->getRequest()->getParam('id') != null);
        $data = $this->getRequest()->getPost();
        if ($data) {
            $employee     = $this->_initEmployee();
            $employeeData = $this->getRequest()->getPost('employee', array());
            $employee->addData($employeeData);
            $employee->setAttributeSetId($employee->getDefaultAttributeSetId());
            if ($useDefaults = $this->getRequest()->getPost('use_default')) {
                foreach ($useDefaults as $attributeCode) {
                    $employee->setData($attributeCode, false);
                }
            }
            try {
                $employee->save();
                $employeeId = $employee->getId();
                $this->_getSession()->addSuccess(
                    Mage::helper('febin_employee')->__('Employee was saved')
                );
            } catch (Mage_Core_Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage())
                    ->setEmployeeData($employeeData);
                $redirectBack = true;
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError(
                    Mage::helper('febin_employee')->__('Error saving employee')
                )
                ->setEmployeeData($employeeData);
                $redirectBack = true;
            }
        }
        if ($redirectBack) {
            $this->_redirect(
                '*/*/edit',
                array(
                    'id'    => $employeeId,
                    '_current'=>true
                )
            );
        } else {
            $this->_redirect('*/*/', array('store'=>$storeId));
        }
    }

    /**
     * delete employee
     *
     * @access public
     * @return void
     * @author Febin Thomas
     */
    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $employee = Mage::getModel('febin_employee/employee')->load($id);
            try {
                $employee->delete();
                $this->_getSession()->addSuccess(
                    Mage::helper('febin_employee')->__('The employee has been deleted.')
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->getResponse()->setRedirect(
            $this->getUrl('*/*/', array('store'=>$this->getRequest()->getParam('store')))
        );
    }

    /**
     * mass delete employee
     *
     * @access public
     * @return void
     * @author Febin Thomas
     */
    public function massDeleteAction()
    {
        $employeeIds = $this->getRequest()->getParam('employee');
        if (!is_array($employeeIds)) {
            $this->_getSession()->addError($this->__('Please select employee.'));
        } else {
            try {
                foreach ($employeeIds as $employeeId) {
                    $employee = Mage::getSingleton('febin_employee/employee')->load($employeeId);
                    Mage::dispatchEvent(
                        'febin_employee_controller_employee_delete',
                        array('employee' => $employee)
                    );
                    $employee->delete();
                }
                $this->_getSession()->addSuccess(
                    Mage::helper('febin_employee')->__('Total of %d record(s) have been deleted.', count($employeeIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass status change - action
     *
     * @access public
     * @return void
     * @author Febin Thomas
     */
    public function massStatusAction()
    {
        $employeeIds = $this->getRequest()->getParam('employee');
        if (!is_array($employeeIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('febin_employee')->__('Please select employee.')
            );
        } else {
            try {
                foreach ($employeeIds as $employeeId) {
                $employee = Mage::getSingleton('febin_employee/employee')->load($employeeId)
                    ->setStatus($this->getRequest()->getParam('status'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d employee were successfully updated.', count($employeeIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('febin_employee')->__('There was an error updating employee.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * grid action
     *
     * @access public
     * @return void
     * @author Febin Thomas
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * restrict access
     *
     * @access protected
     * @return bool
     * @see Mage_Adminhtml_Controller_Action::_isAllowed()
     * @author Febin Thomas
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('febin_employee/employee');
    }

    /**
     * Export employees in CSV format
     *
     * @access public
     * @return void
     * @author Febin Thomas
     */
    public function exportCsvAction()
    {
        $fileName   = 'employees.csv';
        $content    = $this->getLayout()->createBlock('febin_employee/adminhtml_employee_grid')
            ->getCsvFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export employee in Excel format
     *
     * @access public
     * @return void
     * @author Febin Thomas
     */
    public function exportExcelAction()
    {
        $fileName   = 'employee.xls';
        $content    = $this->getLayout()->createBlock('febin_employee/adminhtml_employee_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export employee in XML format
     *
     * @access public
     * @return void
     * @author Febin Thomas
     */
    public function exportXmlAction()
    {
        $fileName   = 'employee.xml';
        $content    = $this->getLayout()->createBlock('febin_employee/adminhtml_employee_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * wysiwyg editor action
     *
     * @access public
     * @return void
     * @author Febin Thomas
     */
    public function wysiwygAction()
    {
        $elementId     = $this->getRequest()->getParam('element_id', md5(microtime()));
        $storeId       = $this->getRequest()->getParam('store_id', 0);
        $storeMediaUrl = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);

        $content = $this->getLayout()->createBlock(
            'febin_employee/adminhtml_employee_helper_form_wysiwyg_content',
            '',
            array(
                'editor_element_id' => $elementId,
                'store_id'          => $storeId,
                'store_media_url'   => $storeMediaUrl,
            )
        );
        $this->getResponse()->setBody($content->toHtml());
    }
}
