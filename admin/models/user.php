<?php 
/**
 * Model Class for SIAK User
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\User\User;
use Joomla\Utilities\ArrayHelper;

class SiakuserModelUser extends AdminModel
{
    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm('com_siakuser.user', 'user', array('control'=>'jform', 'load_data'=>$loadData));
        
        if(empty($form))
        {
            return false;
        }
         
        return $form;
    }

    public function getTable($name = 'User', $prefix = 'SiakuserTable', $options = array())
    {
        return parent::getTable($name, $prefix, $options);
    }

    protected function loadFormData()
    {
        return $this->getItem();
    }

    public function getItem($pk = null)
    {
        $data = parent::getItem();
        $user = Factory::getUser($data->user_id);
        $data->user = $user->name;
        $data->username = $user->username;
        return $data;
    }  
    
    public function publish(&$pks, $value = 1, $task = 'lulus')
	{
		$dispatcher = \JEventDispatcher::getInstance();
		$user = \JFactory::getUser();
		$table = $this->getTable();

        if($task == 'lulus')
        {
            $table->setColumnAlias('published', 'tipe_user');
        }else{
            $table->setColumnAlias('published', 'aktif');
        }
        
		$pks = (array) $pks;

		
		// Access checks.
		foreach ($pks as $i => $pk)
		{
			$table->reset();

			if ($table->load($pk))
			{
				if (!$this->canEditState($table))
				{
					// Prune items that you can't change.
					unset($pks[$i]);

					\JLog::add(\JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'), \JLog::WARNING, 'jerror');

					return false;
				}

				// If the table is checked out by another user, drop it and report to the user trying to change its state.
				if (property_exists($table, 'checked_out') && $table->checked_out && ($table->checked_out != $user->id))
				{
					\JLog::add(\JText::_('JLIB_APPLICATION_ERROR_CHECKIN_USER_MISMATCH'), \JLog::WARNING, 'jerror');

					// Prune items that you can't change.
					unset($pks[$i]);

					return false;
				}

				/**
				 * Prune items that are already at the given state.  Note: Only models whose table correctly
				 * sets 'published' column alias (if different than published) will benefit from this
				 */
				$publishedColumnName = $table->getColumnAlias('published');

				if (property_exists($table, $publishedColumnName) && $table->get($publishedColumnName, $value) == $value)
				{
					unset($pks[$i]);

					continue;
				}
			}
		}

		// Check if there are items to change
		if (!count($pks))
		{
			return true;
		}

		// Attempt to change the state of the records.
		if (!$table->publish($pks, $value, $user->get('id')))
		{
			$this->setError($table->getError());

			return false;
		}

		
		// Clear the component's cache
		$this->cleanCache();

		return true;
	}

}