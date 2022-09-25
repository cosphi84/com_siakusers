<?php
/**
 * com_siakuser 
 * SIAK Mode User
 * Main Controller
 */
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\Utilities\ArrayHelper;

/**
 * com_siakuser base component Cntroller
 */
class SiakuserController extends BaseController
{
    protected $default_view = "users";

    public function __construct($config=array(), MVCFactoryInterface $factory = null)
    {
        $app = Factory::getApplication();
        $filters = $app->input->get('filter', array(), 'array');
        $prodi = ArrayHelper::getValue($filters, 'prodi', null, 'int');
        $app->setUserState('com_siakuser.filter.prodi', $prodi);

        parent::__construct($config, $factory);
    }
}
