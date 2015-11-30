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
 * Blog helper
 *
 * @category    Febin
 * @package     Febin_Employee
 * @author      Febin Thomas
 */
class Febin_Employee_Helper_Blog extends Mage_Core_Helper_Abstract
{

    /**
     * get the url to the blog list page
     *
     * @access public
     * @return string
     * @author Febin Thomas
     */
    public function getBlogsUrl()
    {
        if ($listKey = Mage::getStoreConfig('febin_employee/blog/url_rewrite_list')) {
            return Mage::getUrl('', array('_direct'=>$listKey));
        }
        return Mage::getUrl('febin_employee/blog/index');
    }

    /**
     * check if breadcrumbs can be used
     *
     * @access public
     * @return bool
     * @author Febin Thomas
     */
    public function getUseBreadcrumbs()
    {
        return Mage::getStoreConfigFlag('febin_employee/blog/breadcrumbs');
    }
}
