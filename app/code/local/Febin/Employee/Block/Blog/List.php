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
 * Blog list block
 *
 * @category    Febin
 * @package     Febin_Employee
 * @author Febin Thomas
 */
class Febin_Employee_Block_Blog_List extends Mage_Core_Block_Template
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
        $blogs = Mage::getResourceModel('febin_employee/blog_collection')
                         ->addStoreFilter(Mage::app()->getStore())
                         ->addFieldToFilter('status', 1);
        $blogs->setOrder('blog_titile', 'asc');
        $this->setBlogs($blogs);
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return Febin_Employee_Block_Blog_List
     * @author Febin Thomas
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock(
            'page/html_pager',
            'febin_employee.blog.html.pager'
        )
        ->setCollection($this->getBlogs());
        $this->setChild('pager', $pager);
        $this->getBlogs()->load();
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
