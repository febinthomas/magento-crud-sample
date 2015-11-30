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
 * Blog comment list block
 *
 * @category    Febin
 * @package     Febin_Employee
 * @author Febin Thomas
 */
class Febin_Employee_Block_Blog_Comment_List extends Mage_Core_Block_Template
{
    /**
     * initialize
     *
     * @access public
     * @author Febin Thomas
     */
    public function __construct()
    {
        parent::__construct();
        $blog = $this->getBlog();
        $comments = Mage::getResourceModel('febin_employee/blog_comment_collection')
            ->addFieldToFilter('blog_id', $blog->getId())
            ->addStoreFilter(Mage::app()->getStore())
             ->addFieldToFilter('status', 1);
        $comments->setOrder('created_at', 'asc');
        $this->setComments($comments);
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return Febin_Employee_Block_Blog_Comment_List
     * @author Febin Thomas
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock(
            'page/html_pager',
            'febin_employee.blog.html.pager'
        )
        ->setCollection($this->getComments());
        $this->setChild('pager', $pager);
        $this->getComments()->load();
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
    /**
     * get the current blog
     *
     * @access protected
     * @return Febin_Employee_Model_Blog
     * @author Febin Thomas
     */
    public function getBlog()
    {
        return Mage::registry('current_blog');
    }
}
