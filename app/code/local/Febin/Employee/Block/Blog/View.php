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
 * Blog view block
 *
 * @category    Febin
 * @package     Febin_Employee
 * @author      Febin Thomas
 */
class Febin_Employee_Block_Blog_View extends Mage_Core_Block_Template
{
    /**
     * get the current blog
     *
     * @access public
     * @return mixed (Febin_Employee_Model_Blog|null)
     * @author Febin Thomas
     */
    public function getCurrentBlog()
    {
        return Mage::registry('current_blog');
    }
}
