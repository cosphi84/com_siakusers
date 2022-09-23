<?php
/**
 * com_siakuser 
 * SIAK Mode User
 */
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

JLoader::register('SiakuserHelper', __DIR__.'/helpers/siakuser.php');

$controller = BaseController::getInstance('Siakuser');
$controller->execute(Factory::getApplication()->input->get('task', 'display'));
$controller->redirect();