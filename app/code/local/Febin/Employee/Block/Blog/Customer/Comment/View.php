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
 * Blog customer comments list
 *
 * @category    Febin
 * @package     Febin_Employee
 * @author      Febin Thomas
 */
class Febin_Employee_Block_Blog_Customer_Comment_View extends Mage_Customer_Block_Account_Dashboard
{
    /**
     * get current comment
     *
     * @access public
     * @return Febin_Employee_Model_Blog_Comment
     * @author Febin Thomas
     */
    public function getComment()
    {
        return Mage::registry('current_comment');
    }

    /**
     * get current blog
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
