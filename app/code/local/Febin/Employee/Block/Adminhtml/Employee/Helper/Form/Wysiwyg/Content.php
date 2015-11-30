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
 * wysiwyg helper
 * @category   Febin
 * @package    Febin_Employee
 * @author      Febin Thomas
 */
class Febin_Employee_Block_Adminhtml_Employee_Helper_Form_Wysiwyg_Content extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Prepare form.
     * Adding editor field to render
     *
     * @access protected
     * @return Febin_Employee_Block_Adminhtml_Employee_Helper_Form_Wysiwyg_Content
     * @author Febin Thomas
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(
            array(
                'id'     => 'wysiwyg_edit_form',
                'action' => $this->getData('action'),
                'method' => 'post'
            )
        );
        $config['document_base_url']     = $this->getData('store_media_url');
        $config['store_id']              = $this->getData('store_id');
        $config['add_variables']         = false;
        $config['add_widgets']           = false;
        $config['add_directives']        = true;
        $config['use_container']         = true;
        $config['container_class']       = 'hor-scroll';

        $editorConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig($config);
        $editorConfig->setData(
            'files_browser_window_url',
            Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg_images/index')
        );
        $form->addField(
            $this->getData('editor_element_id'),
            'editor',
            array(
                'name'       => 'content',
                'style'      => 'width:725px;height:460px',
                'required'   => true,
                'force_load' => true,
                'config'     => $editorConfig
            )
        );
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
