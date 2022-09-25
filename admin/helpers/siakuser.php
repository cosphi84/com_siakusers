<?php
defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\User\User;
use Joomla\Utilities\ArrayHelper;

class SiakuserHelper extends ContentHelper
{
    public static function submenu($vname)
    {
        $canDo = ContentHelper::getActions('com_siakuser');

        if($canDo->get('core.manage'))
        {
            JHtmlSidebar::addEntry(
                Text::_('COM_SIAKUSER_SUBMENU_MANAGE_MODUL'),
                Route::_('index.php?option=com_config&view=component&component=com_siakuser')
            );
        }

        JHtmlSidebar::addEntry(
            Text::_('COM_SIAKUSER_SUBMENU_USERLIST'),
            Route::_('index.php?option=com_siakuser&view=users'), 
            (bool) $vname == 'users'
        );
    }

    /**
     * getSiakUserGroup 
     * Mengambil usergrop valid dalam SIAK.
     * @param $group string Nama grup yang akan dipilih. Null akan memberikan semua grup
     * @return int grup id atau Array usergrop
     */
    public static function getSiakUserGroup($group = null)
    {
        $params = ComponentHelper::getParams('com_siak');
        
       return $params;
    }
}