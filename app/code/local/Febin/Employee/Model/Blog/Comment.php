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
 * Blog comment model
 *
 * @category    Febin
 * @package     Febin_Employee
 * @author      Febin Thomas
 */
class Febin_Employee_Model_Blog_Comment extends Mage_Core_Model_Abstract
{
    const STATUS_PENDING  = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'febin_employee_blog_comment';
    const CACHE_TAG = 'febin_employee_blog_comment';
    /**
     * Prefix of model events names
     * @var string
     */
    protected $_eventPrefix = 'febin_employee_blog_comment';

    /**
     * Parameter name in event
     * @var string
     */
    protected $_eventObject = 'comment';

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
        $this->_init('febin_employee/blog_comment');
    }

    /**
     * before save blog comment
     *
     * @access protected
     * @return Febin_Employee_Model_Blog_Comment
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
     * validate comment
     *
     * @access public
     * @return array|bool
     * @author Febin Thomas
     */
    public function validate()
    {
        $errors = array();

        if (!Zend_Validate::is($this->getTitle(), 'NotEmpty')) {
            $errors[] = Mage::helper('review')->__('Comment title can\'t be empty');
        }

        if (!Zend_Validate::is($this->getName(), 'NotEmpty')) {
            $errors[] = Mage::helper('review')->__('Your name can\'t be empty');
        }

        if (!Zend_Validate::is($this->getComment(), 'NotEmpty')) {
            $errors[] = Mage::helper('review')->__('Comment can\'t be empty');
        }

        if (empty($errors)) {
            return true;
        }
        return $errors;
    }
}
