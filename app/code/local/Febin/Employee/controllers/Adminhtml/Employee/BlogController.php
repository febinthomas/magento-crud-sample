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
 * Blog admin controller
 *
 * @category    Febin
 * @package     Febin_Employee
 * @author      Febin Thomas
 */
class Febin_Employee_Adminhtml_Employee_BlogController extends Febin_Employee_Controller_Adminhtml_Employee
{
    /**
     * init the blog
     *
     * @access protected
     * @return Febin_Employee_Model_Blog
     */
    protected function _initBlog()
    {
        $blogId  = (int) $this->getRequest()->getParam('id');
        $blog    = Mage::getModel('febin_employee/blog');
        if ($blogId) {
            $blog->load($blogId);
        }
        Mage::register('current_blog', $blog);
        return $blog;
    }

    /**
     * default action
     *
     * @access public
     * @return void
     * @author Febin Thomas
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_title(Mage::helper('febin_employee')->__('Febin'))
             ->_title(Mage::helper('febin_employee')->__('Blog'));
        $this->renderLayout();
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
        $this->loadLayout()->renderLayout();
    }

    /**
     * edit blog - action
     *
     * @access public
     * @return void
     * @author Febin Thomas
     */
    public function editAction()
    {
        $blogId    = $this->getRequest()->getParam('id');
        $blog      = $this->_initBlog();
        if ($blogId && !$blog->getId()) {
            $this->_getSession()->addError(
                Mage::helper('febin_employee')->__('This blog no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getBlogData(true);
        if (!empty($data)) {
            $blog->setData($data);
        }
        Mage::register('blog_data', $blog);
        $this->loadLayout();
        $this->_title(Mage::helper('febin_employee')->__('Febin'))
             ->_title(Mage::helper('febin_employee')->__('Blog'));
        if ($blog->getId()) {
            $this->_title($blog->getBlogTitile());
        } else {
            $this->_title(Mage::helper('febin_employee')->__('Add blog'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new blog action
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
     * save blog - action
     *
     * @access public
     * @return void
     * @author Febin Thomas
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('blog')) {
            try {
                $blog = $this->_initBlog();
                $blog->addData($data);
                $blog->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('febin_employee')->__('Blog was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $blog->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setBlogData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('febin_employee')->__('There was a problem saving the blog.')
                );
                Mage::getSingleton('adminhtml/session')->setBlogData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('febin_employee')->__('Unable to find blog to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete blog - action
     *
     * @access public
     * @return void
     * @author Febin Thomas
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $blog = Mage::getModel('febin_employee/blog');
                $blog->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('febin_employee')->__('Blog was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('febin_employee')->__('There was an error deleting blog.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('febin_employee')->__('Could not find blog to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete blog - action
     *
     * @access public
     * @return void
     * @author Febin Thomas
     */
    public function massDeleteAction()
    {
        $blogIds = $this->getRequest()->getParam('blog');
        if (!is_array($blogIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('febin_employee')->__('Please select blog to delete.')
            );
        } else {
            try {
                foreach ($blogIds as $blogId) {
                    $blog = Mage::getModel('febin_employee/blog');
                    $blog->setId($blogId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('febin_employee')->__('Total of %d blog were successfully deleted.', count($blogIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('febin_employee')->__('There was an error deleting blog.')
                );
                Mage::logException($e);
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
        $blogIds = $this->getRequest()->getParam('blog');
        if (!is_array($blogIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('febin_employee')->__('Please select blog.')
            );
        } else {
            try {
                foreach ($blogIds as $blogId) {
                $blog = Mage::getSingleton('febin_employee/blog')->load($blogId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d blog were successfully updated.', count($blogIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('febin_employee')->__('There was an error updating blog.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * export as csv - action
     *
     * @access public
     * @return void
     * @author Febin Thomas
     */
    public function exportCsvAction()
    {
        $fileName   = 'blog.csv';
        $content    = $this->getLayout()->createBlock('febin_employee/adminhtml_blog_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as MsExcel - action
     *
     * @access public
     * @return void
     * @author Febin Thomas
     */
    public function exportExcelAction()
    {
        $fileName   = 'blog.xls';
        $content    = $this->getLayout()->createBlock('febin_employee/adminhtml_blog_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as xml - action
     *
     * @access public
     * @return void
     * @author Febin Thomas
     */
    public function exportXmlAction()
    {
        $fileName   = 'blog.xml';
        $content    = $this->getLayout()->createBlock('febin_employee/adminhtml_blog_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Check if admin has permissions to visit related pages
     *
     * @access protected
     * @return boolean
     * @author Febin Thomas
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('febin_employee/blog');
    }
}
