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
 * Blog admin edit tabs
 *
 * @category    Febin
 * @package     Febin_Employee
 * @author      Febin Thomas
 */
class Febin_Employee_Block_Adminhtml_Blog_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize Tabs
     *
     * @access public
     * @author Febin Thomas
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('blog_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('febin_employee')->__('Blog'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return Febin_Employee_Block_Adminhtml_Blog_Edit_Tabs
     * @author Febin Thomas
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_blog',
            array(
                'label'   => Mage::helper('febin_employee')->__('Blog'),
                'title'   => Mage::helper('febin_employee')->__('Blog'),
                'content' => $this->getLayout()->createBlock(
                    'febin_employee/adminhtml_blog_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        $this->addTab(
            'form_meta_blog',
            array(
                'label'   => Mage::helper('febin_employee')->__('Meta'),
                'title'   => Mage::helper('febin_employee')->__('Meta'),
                'content' => $this->getLayout()->createBlock(
                    'febin_employee/adminhtml_blog_edit_tab_meta'
                )
                ->toHtml(),
            )
        );
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addTab(
                'form_store_blog',
                array(
                    'label'   => Mage::helper('febin_employee')->__('Store views'),
                    'title'   => Mage::helper('febin_employee')->__('Store views'),
                    'content' => $this->getLayout()->createBlock(
                        'febin_employee/adminhtml_blog_edit_tab_stores'
                    )
                    ->toHtml(),
                )
            );
        }
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve blog entity
     *
     * @access public
     * @return Febin_Employee_Model_Blog
     * @author Febin Thomas
     */
    public function getBlog()
    {
        return Mage::registry('current_blog');
    }
}
