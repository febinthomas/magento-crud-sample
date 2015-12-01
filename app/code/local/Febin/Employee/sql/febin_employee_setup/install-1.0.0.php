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
 * Employee module install script
 *
 * @category    Febin
 * @package     Febin_Employee
 * @author      Febin Thomas
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('febin_employee/employee'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'unsigned'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Entity ID'
    )
    ->addColumn(
        'entity_type_id',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        null,
        array(
            'unsigned'  => true,
            'nullable'  => false,
            'default'   => '0',
        ),
        'Entity Type ID'
    )
    ->addColumn(
        'attribute_set_id',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        null,
        array(
            'unsigned'  => true,
            'nullable'  => false,
            'default'   => '0',
        ),
        'Attribute Set ID'
    )

    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null, array(),
        'Creation Time'
    )
    ->addColumn(
        'updated_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Update Time'
    )
    ->addIndex(
        $this->getIdxName(
            'febin_employee/employee',
            array('entity_type_id')
        ),
        array('entity_type_id')
    )
    ->addIndex(
        $this->getIdxName(
            'febin_employee/employee',
            array('attribute_set_id')
        ),
        array('attribute_set_id')
    )
    ->addForeignKey(
        $this->getFkName(
            'febin_employee/employee',
            'attribute_set_id',
            'eav/attribute_set',
            'attribute_set_id'
        ),
        'attribute_set_id',
        $this->getTable('eav/attribute_set'),
        'attribute_set_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $this->getFkName(
            'febin_employee/employee',
            'entity_type_id',
            'eav/entity_type',
            'entity_type_id'
        ),
        'entity_type_id',
        $this->getTable('eav/entity_type'),
        'entity_type_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Employee Table');
$this->getConnection()->createTable($table);

$employeeEav = array();
$employeeEav['int'] = array(
    'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'length'    => null,
    'comment'   => 'Employee Datetime Attribute Backend Table'
);

$employeeEav['varchar'] = array(
    'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'    => 255,
    'comment'   => 'Employee Varchar Attribute Backend Table'
);

$employeeEav['text'] = array(
    'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'    => '64k',
    'comment'   => 'Employee Text Attribute Backend Table'
);

$employeeEav['datetime'] = array(
    'type'      => Varien_Db_Ddl_Table::TYPE_DATETIME,
    'length'    => null,
    'comment'   => 'Employee Datetime Attribute Backend Table'
);

$employeeEav['decimal'] = array(
    'type'      => Varien_Db_Ddl_Table::TYPE_DECIMAL,
    'length'    => '12,4',
    'comment'   => 'Employee Datetime Attribute Backend Table'
);

foreach ($employeeEav as $type => $options) {
    $table = $this->getConnection()
        ->newTable($this->getTable(array('febin_employee/employee', $type)))
        ->addColumn(
            'value_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'identity'  => true,
                'nullable'  => false,
                'primary'   => true,
            ),
            'Value ID'
        )
        ->addColumn(
            'entity_type_id',
            Varien_Db_Ddl_Table::TYPE_SMALLINT,
            null,
            array(
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
            ),
            'Entity Type ID'
        )
        ->addColumn(
            'attribute_id',
            Varien_Db_Ddl_Table::TYPE_SMALLINT,
            null,
            array(
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
            ),
            'Attribute ID'
        )
        ->addColumn(
            'store_id',
            Varien_Db_Ddl_Table::TYPE_SMALLINT,
            null,
            array(
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
            ),
            'Store ID'
        )
        ->addColumn(
            'entity_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
            ),
            'Entity ID'
        )
        ->addColumn(
            'value',
            $options['type'],
            $options['length'], array(),
            'Value'
        )
        ->addIndex(
            $this->getIdxName(
                array('febin_employee/employee', $type),
                array('entity_id', 'attribute_id', 'store_id'),
                Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
            ),
            array('entity_id', 'attribute_id', 'store_id'),
            array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
        )
        ->addIndex(
            $this->getIdxName(
                array('febin_employee/employee', $type),
                array('store_id')
            ),
            array('store_id')
        )
        ->addIndex(
            $this->getIdxName(
                array('febin_employee/employee', $type),
                array('entity_id')
            ),
            array('entity_id')
        )
        ->addIndex(
            $this->getIdxName(
                array('febin_employee/employee', $type),
                array('attribute_id')
            ),
            array('attribute_id')
        )
        ->addForeignKey(
            $this->getFkName(
                array('febin_employee/employee', $type),
                'attribute_id',
                'eav/attribute',
                'attribute_id'
            ),
            'attribute_id',
            $this->getTable('eav/attribute'),
            'attribute_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE,
            Varien_Db_Ddl_Table::ACTION_CASCADE
        )
        ->addForeignKey(
            $this->getFkName(
                array('febin_employee/employee', $type),
                'entity_id',
                'febin_employee/employee',
                'entity_id'
            ),
            'entity_id',
            $this->getTable('febin_employee/employee'),
            'entity_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE,
            Varien_Db_Ddl_Table::ACTION_CASCADE
        )
        ->addForeignKey(
            $this->getFkName(
                array('febin_employee/employee', $type),
                'store_id',
                'core/store',
                'store_id'
            ),
            'store_id',
            $this->getTable('core/store'),
            'store_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE,
            Varien_Db_Ddl_Table::ACTION_CASCADE
        )
        ->setComment($options['comment']);
    $this->getConnection()->createTable($table);
}
$table = $this->getConnection()
    ->newTable($this->getTable('febin_employee/eav_attribute'))
    ->addColumn(
        'attribute_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Attribute ID'
    )
    ->addColumn(
        'is_global',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(),
        'Attribute scope'
    )
    ->addColumn(
        'position',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(),
        'Attribute position'
    )
    ->addColumn(
        'is_wysiwyg_enabled',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(),
        'Attribute uses WYSIWYG'
    )
    ->addColumn(
        'is_visible',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(),
        'Attribute is visible'
    )
    ->setComment('Employee attribute table');
$this->getConnection()->createTable($table);

$this->installEntities();

$this->endSetup();
