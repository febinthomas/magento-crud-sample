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
 * Blog admin edit form
 *
 * @category    Febin
 * @package     Febin_Employee
 * @author      Febin Thomas
 */
class Febin_Employee_Block_Adminhtml_Blog_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Febin Thomas
     */
    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'febin_employee';
        $this->_controller = 'adminhtml_blog';
        $this->_updateButton(
            'save',
            'label',
            Mage::helper('febin_employee')->__('Save Blog')
        );
        $this->_updateButton(
            'delete',
            'label',
            Mage::helper('febin_employee')->__('Delete Blog')
        );
        $this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('febin_employee')->__('Save And Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
            ),
            -100
        );
        $this->_formScripts[] = "
            function saveAndContinueEdit() {
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     * get the edit form header
     *
     * @access public
     * @return string
     * @author Febin Thomas
     */
    public function getHeaderText()
    {
        if (Mage::registry('current_blog') && Mage::registry('current_blog')->getId()) {
            return Mage::helper('febin_employee')->__(
                "Edit Blog '%s'",
                $this->escapeHtml(Mage::registry('current_blog')->getBlogTitile())
            );
        } else {
            return Mage::helper('febin_employee')->__('Add Blog');
        }
    }
}
