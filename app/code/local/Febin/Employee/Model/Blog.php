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
 * Blog model
 *
 * @category    Febin
 * @package     Febin_Employee
 * @author      Febin Thomas
 */
class Febin_Employee_Model_Blog extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'febin_employee_blog';
    const CACHE_TAG = 'febin_employee_blog';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'febin_employee_blog';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'blog';

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
        $this->_init('febin_employee/blog');
    }

    /**
     * before save blog
     *
     * @access protected
     * @return Febin_Employee_Model_Blog
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
     * get the url to the blog details page
     *
     * @access public
     * @return string
     * @author Febin Thomas
     */
    public function getBlogUrl()
    {
        if ($this->getUrlKey()) {
            $urlKey = '';
            if ($prefix = Mage::getStoreConfig('febin_employee/blog/url_prefix')) {
                $urlKey .= $prefix.'/';
            }
            $urlKey .= $this->getUrlKey();
            if ($suffix = Mage::getStoreConfig('febin_employee/blog/url_suffix')) {
                $urlKey .= '.'.$suffix;
            }
            return Mage::getUrl('', array('_direct'=>$urlKey));
        }
        return Mage::getUrl('febin_employee/blog/view', array('id'=>$this->getId()));
    }

    /**
     * check URL key
     *
     * @access public
     * @param string $urlKey
     * @param bool $active
     * @return mixed
     * @author Febin Thomas
     */
    public function checkUrlKey($urlKey, $active = true)
    {
        return $this->_getResource()->checkUrlKey($urlKey, $active);
    }

    /**
     * get the blog Blog content
     *
     * @access public
     * @return string
     * @author Febin Thomas
     */
    public function getBlogContent()
    {
        $blog_content = $this->getData('blog_content');
        $helper = Mage::helper('cms');
        $processor = $helper->getBlockTemplateProcessor();
        $html = $processor->filter($blog_content);
        return $html;
    }

    /**
     * save blog relation
     *
     * @access public
     * @return Febin_Employee_Model_Blog
     * @author Febin Thomas
     */
    protected function _afterSave()
    {
        return parent::_afterSave();
    }

    /**
     * check if comments are allowed
     *
     * @access public
     * @return array
     * @author Febin Thomas
     */
    public function getAllowComments()
    {
        if ($this->getData('allow_comment') == Febin_Employee_Model_Adminhtml_Source_Yesnodefault::NO) {
            return false;
        }
        if ($this->getData('allow_comment') == Febin_Employee_Model_Adminhtml_Source_Yesnodefault::YES) {
            return true;
        }
        return Mage::getStoreConfigFlag('febin_employee/blog/allow_comment');
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
        $values['allow_comment'] = Febin_Employee_Model_Adminhtml_Source_Yesnodefault::USE_DEFAULT;
        return $values;
    }
    
}
