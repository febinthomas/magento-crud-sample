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
 * Blog front contrller
 *
 * @category    Febin
 * @package     Febin_Employee
 * @author      Febin Thomas
 */
class Febin_Employee_BlogController extends Mage_Core_Controller_Front_Action
{

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
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if (Mage::helper('febin_employee/blog')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                        'label' => Mage::helper('febin_employee')->__('Home'),
                        'link'  => Mage::getUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'blogs',
                    array(
                        'label' => Mage::helper('febin_employee')->__('Blog'),
                        'link'  => '',
                    )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', Mage::helper('febin_employee/blog')->getBlogsUrl());
        }
        if ($headBlock) {
            $headBlock->setTitle(Mage::getStoreConfig('febin_employee/blog/meta_title'));
            $headBlock->setKeywords(Mage::getStoreConfig('febin_employee/blog/meta_keywords'));
            $headBlock->setDescription(Mage::getStoreConfig('febin_employee/blog/meta_description'));
        }
        $this->renderLayout();
    }

    /**
     * init Blog
     *
     * @access protected
     * @return Febin_Employee_Model_Blog
     * @author Febin Thomas
     */
    protected function _initBlog()
    {
        $blogId   = $this->getRequest()->getParam('id', 0);
        $blog     = Mage::getModel('febin_employee/blog')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($blogId);
        if (!$blog->getId()) {
            return false;
        } elseif (!$blog->getStatus()) {
            return false;
        }
        return $blog;
    }

    /**
     * view blog action
     *
     * @access public
     * @return void
     * @author Febin Thomas
     */
    public function viewAction()
    {
        $blog = $this->_initBlog();
        if (!$blog) {
            $this->_forward('no-route');
            return;
        }
        Mage::register('current_blog', $blog);
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($root = $this->getLayout()->getBlock('root')) {
            $root->addBodyClass('employee-blog employee-blog' . $blog->getId());
        }
        if (Mage::helper('febin_employee/blog')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                        'label'    => Mage::helper('febin_employee')->__('Home'),
                        'link'     => Mage::getUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'blogs',
                    array(
                        'label' => Mage::helper('febin_employee')->__('Blog'),
                        'link'  => Mage::helper('febin_employee/blog')->getBlogsUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'blog',
                    array(
                        'label' => $blog->getBlogTitile(),
                        'link'  => '',
                    )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', $blog->getBlogUrl());
        }
        if ($headBlock) {
            if ($blog->getMetaTitle()) {
                $headBlock->setTitle($blog->getMetaTitle());
            } else {
                $headBlock->setTitle($blog->getBlogTitile());
            }
            $headBlock->setKeywords($blog->getMetaKeywords());
            $headBlock->setDescription($blog->getMetaDescription());
        }
        $this->renderLayout();
    }

    /**
     * Submit new comment action
     * @access public
     * @author Febin Thomas
     */
    public function commentpostAction()
    {
        $data   = $this->getRequest()->getPost();
        $blog = $this->_initBlog();
        $session    = Mage::getSingleton('core/session');
        if ($blog) {
            if ($blog->getAllowComments()) {
                if ((Mage::getSingleton('customer/session')->isLoggedIn() ||
                    Mage::getStoreConfigFlag('febin_employee/blog/allow_guest_comment'))) {
                    $comment  = Mage::getModel('febin_employee/blog_comment')->setData($data);
                    $validate = $comment->validate();
                    if ($validate === true) {
                        try {
                            $comment->setBlogId($blog->getId())
                                ->setStatus(Febin_Employee_Model_Blog_Comment::STATUS_PENDING)
                                ->setCustomerId(Mage::getSingleton('customer/session')->getCustomerId())
                                ->setStores(array(Mage::app()->getStore()->getId()))
                                ->save();
                            $session->addSuccess($this->__('Your comment has been accepted for moderation.'));
                        } catch (Exception $e) {
                            $session->setBlogCommentData($data);
                            $session->addError($this->__('Unable to post the comment.'));
                        }
                    } else {
                        $session->setBlogCommentData($data);
                        if (is_array($validate)) {
                            foreach ($validate as $errorMessage) {
                                $session->addError($errorMessage);
                            }
                        } else {
                            $session->addError($this->__('Unable to post the comment.'));
                        }
                    }
                } else {
                    $session->addError($this->__('Guest comments are not allowed'));
                }
            } else {
                $session->addError($this->__('This blog does not allow comments'));
            }
        }
        $this->_redirectReferer();
    }
}
