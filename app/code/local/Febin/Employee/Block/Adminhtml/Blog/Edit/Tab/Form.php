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
 * Blog edit form tab
 *
 * @category    Febin
 * @package     Febin_Employee
 * @author      Febin Thomas
 */
class Febin_Employee_Block_Adminhtml_Blog_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return Febin_Employee_Block_Adminhtml_Blog_Edit_Tab_Form
     * @author Febin Thomas
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('blog_');
        $form->setFieldNameSuffix('blog');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'blog_form',
            array('legend' => Mage::helper('febin_employee')->__('Blog'))
        );
        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig();

        $fieldset->addField(
            'blog_titile',
            'text',
            array(
                'label' => Mage::helper('febin_employee')->__('Blog Titile'),
                'name'  => 'blog_titile',
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'blog_content',
            'editor',
            array(
                'label' => Mage::helper('febin_employee')->__('Blog content'),
                'name'  => 'blog_content',
            'config' => $wysiwygConfig,
                'required'  => true,
                'class' => 'required-entry',

           )
        );
        $fieldset->addField(
            'url_key',
            'text',
            array(
                'label' => Mage::helper('febin_employee')->__('Url key'),
                'name'  => 'url_key',
                'note'  => Mage::helper('febin_employee')->__('Relative to Website Base URL')
            )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('febin_employee')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('febin_employee')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('febin_employee')->__('Disabled'),
                    ),
                ),
            )
        );
        if (Mage::app()->isSingleStoreMode()) {
            $fieldset->addField(
                'store_id',
                'hidden',
                array(
                    'name'      => 'stores[]',
                    'value'     => Mage::app()->getStore(true)->getId()
                )
            );
            Mage::registry('current_blog')->setStoreId(Mage::app()->getStore(true)->getId());
        }
        $fieldset->addField(
            'allow_comment',
            'select',
            array(
                'label' => Mage::helper('febin_employee')->__('Allow Comments'),
                'name'  => 'allow_comment',
                'values'=> Mage::getModel('febin_employee/adminhtml_source_yesnodefault')->toOptionArray()
            )
        );
        $formValues = Mage::registry('current_blog')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getBlogData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getBlogData());
            Mage::getSingleton('adminhtml/session')->setBlogData(null);
        } elseif (Mage::registry('current_blog')) {
            $formValues = array_merge($formValues, Mage::registry('current_blog')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
