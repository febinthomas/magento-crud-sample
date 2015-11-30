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
 * Employee resource model
 *
 * @category    Febin
 * @package     Febin_Employee
 * @author      Febin Thomas
 */
class Febin_Employee_Model_Resource_Employee extends Mage_Catalog_Model_Resource_Abstract
{


    /**
     * constructor
     *
     * @access public
     * @author Febin Thomas
     */
    public function __construct()
    {
        $resource = Mage::getSingleton('core/resource');
        $this->setType('febin_employee_employee')
            ->setConnection(
                $resource->getConnection('employee_read'),
                $resource->getConnection('employee_write')
            );

    }

    /**
     * wrapper for main table getter
     *
     * @access public
     * @return string
     * @author Febin Thomas
     */
    public function getMainTable()
    {
        return $this->getEntityTable();
    }
}
