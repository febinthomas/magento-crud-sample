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
 * Employee admin grid block
 *
 * @category    Febin
 * @package     Febin_Employee
 * @author      Febin Thomas
 */
class Febin_Employee_Block_Adminhtml_Employee_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * constructor
     *
     * @access public
     * @author Febin Thomas
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('employeeGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return Febin_Employee_Block_Adminhtml_Employee_Grid
     * @author Febin Thomas
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('febin_employee/employee')
            ->getCollection()
            ->addAttributeToSelect('employee_middlename')
            ->addAttributeToSelect('employee_surname')
            ->addAttributeToSelect('employee_email')
            ->addAttributeToSelect('employee_dob')
            ->addAttributeToSelect('status');
        
        $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
        $store = $this->_getStore();
        $collection->joinAttribute(
            'employee_name', 
            'febin_employee_employee/employee_name', 
            'entity_id', 
            null, 
            'inner', 
            $adminStore
        );
        if ($store->getId()) {
            $collection->joinAttribute(
                'febin_employee_employee_employee_name', 
                'febin_employee_employee/employee_name', 
                'entity_id', 
                null, 
                'inner', 
                $store->getId()
            );
        }

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return Febin_Employee_Block_Adminhtml_Employee_Grid
     * @author Febin Thomas
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('febin_employee')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'employee_name',
            array(
                'header'    => Mage::helper('febin_employee')->__('First Name'),
                'align'     => 'left',
                'index'     => 'employee_name',
            )
        );
        
        if ($this->_getStore()->getId()) {
            $this->addColumn(
                'febin_employee_employee_employee_name', 
                array(
                    'header'    => Mage::helper('febin_employee')->__('First Name in %s', $this->_getStore()->getName()),
                    'align'     => 'left',
                    'index'     => 'febin_employee_employee_employee_name',
                )
            );
        }

        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('febin_employee')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('febin_employee')->__('Enabled'),
                    '0' => Mage::helper('febin_employee')->__('Disabled'),
                )
            )
        );
        $this->addColumn(
            'employee_middlename',
            array(
                'header' => Mage::helper('febin_employee')->__('Middle Name'),
                'index'  => 'employee_middlename',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'employee_surname',
            array(
                'header' => Mage::helper('febin_employee')->__('SurName'),
                'index'  => 'employee_surname',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'employee_email',
            array(
                'header' => Mage::helper('febin_employee')->__('Email'),
                'index'  => 'employee_email',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'employee_dob',
            array(
                'header' => Mage::helper('febin_employee')->__('Date of Birth'),
                'index'  => 'employee_dob',
                'type'=> 'date',

            )
        );
        $this->addColumn(
            'created_at',
            array(
                'header' => Mage::helper('febin_employee')->__('Created at'),
                'index'  => 'created_at',
                'width'  => '120px',
                'type'   => 'datetime',
            )
        );
        $this->addColumn(
            'updated_at',
            array(
                'header'    => Mage::helper('febin_employee')->__('Updated at'),
                'index'     => 'updated_at',
                'width'     => '120px',
                'type'      => 'datetime',
            )
        );
        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('febin_employee')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('febin_employee')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('febin_employee')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('febin_employee')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('febin_employee')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * get the selected store
     *
     * @access protected
     * @return Mage_Core_Model_Store
     * @author Febin Thomas
     */
    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return Febin_Employee_Block_Adminhtml_Employee_Grid
     * @author Febin Thomas
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('employee');
        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label'=> Mage::helper('febin_employee')->__('Delete'),
                'url'  => $this->getUrl('*/*/massDelete'),
                'confirm'  => Mage::helper('febin_employee')->__('Are you sure?')
            )
        );
        $this->getMassactionBlock()->addItem(
            'status',
            array(
                'label'      => Mage::helper('febin_employee')->__('Change status'),
                'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                'additional' => array(
                    'status' => array(
                        'name'   => 'status',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('febin_employee')->__('Status'),
                        'values' => array(
                            '1' => Mage::helper('febin_employee')->__('Enabled'),
                            '0' => Mage::helper('febin_employee')->__('Disabled'),
                        )
                    )
                )
            )
        );
        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param Febin_Employee_Model_Employee
     * @return string
     * @author Febin Thomas
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /**
     * get the grid url
     *
     * @access public
     * @return string
     * @author Febin Thomas
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
}
