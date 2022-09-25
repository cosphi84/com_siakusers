<?php
/**
 * SIAK user Controller
 */

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\FormController;


class SiakuserControllerUser extends FormController
{
   public function getModel($name = 'User', $prefix = 'SiakuserModel', $config = array('ignore_request' => true))
   {
        return parent::getModel($name, $prefix, $config);
   }
}