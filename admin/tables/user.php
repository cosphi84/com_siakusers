<?php 
/**
 * Table Class SIAK User
 */

use Joomla\CMS\Date\Date;
use Joomla\CMS\Table\Table;

defined('_JEXEC') or die;

class SiakuserTableUser extends Table
{
    public function __construct(&$db)
    {
        parent::__construct('#__siak_user', 'id', $db);
    }

    public function check()
    {
        try{
            parent::check();
        }catch(Exception $error)
        {
            $this->setError($error->getMessage());

            return false;
        }

        if($this->status <= 0) // lock transaksi ketika inative
        {
            $this->aktif = 0;
        }

        $this->last_update = Date::getInstance()->toSql();
        return true;
    }
}