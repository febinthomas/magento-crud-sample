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
class Febin_Employee_Model_Adminhtml_Search_Blog extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return Febin_Employee_Model_Adminhtml_Search_Blog
     * @author Febin Thomas
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('febin_employee/blog_collection')
            ->addFieldToFilter('blog_titile', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $blog) {
            $arr[] = array(
                'id'          => 'blog/1/'.$blog->getId(),
                'type'        => Mage::helper('febin_employee')->__('Blog'),
                'name'        => $blog->getBlogTitile(),
                'description' => $blog->getBlogTitile(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/employee_blog/edit',
                    array('id'=>$blog->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
