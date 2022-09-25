<?php
/**
 * SIAK mode user
 * Model class untuk SIAK USER
 */

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel;

class SiakuserModelUsers extends ListModel
{
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'prodi', 'jurusan', 'role', 'status',
                'u.name', 'u.username', 'su.id', 'su.status', 'su.prodi', 'su.tahun',
                'su.aktif', 'su.reset', 'su.id'
            );
        }

        parent::__construct($config);
    }

    protected function getListQuery()
    {
        $userGroup = (array) SiakuserHelper::getSiakUserGroup();
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $query->select('su.*')->from($db->quoteName('#__siak_user', 'su'));
        $query->select(array('u.name', 'u.username', 'u.block'))
                ->leftJoin('#__users AS u ON u.id = su.user_id');

        $query->select('p.title AS programStudi')
            ->leftJoin('#__siak_prodi AS p ON p.id = su.prodi');

        $query->select('j.title AS konsentrasi')
            ->leftJoin('#__siak_jurusan AS j ON j.id = su.jurusan');

        $query->select('k.title AS kelasMhs')
            ->leftJoin('#__siak_kelas_mahasiswa AS k ON k.id = su.kelas');

        if (!empty($search = $this->state->get('filter.search'))) {
            if (strpos($search, ':')) {
                $key = explode(':', $search);
                if (strtolower($key[0]) == 'tahun' && is_numeric($key[1])) {
                    $query->where($db->quoteName('su.angkatan'). ' = '. (int) $key[1]);
                    $query->where($db->quoteName('su.role') .' = '. 0);
                }
            } else {
                $search = $db->quote('%'. $search. '%');
                $searchs = array(
                    $db->quoteName('u.name'). ' LIKE '. $search,
                    $db->quoteName('u.username'). ' LIKE '. $search,

                );

                $query->where('('. implode(' OR ', $searchs). ')');
            }
        }

        if (is_numeric($status = $this->state->get('filter.status'))) {
            $query->where($db->quoteName('su.status') . ' = '. (int) $status);
        } else {
            $query->where($db->quoteName('su.status'). ' = ' . 1);
        }

        if (is_numeric($prodi = $this->state->get('filter.prodi'))) {
            $query->where($db->quoteName('su.prodi'). ' = '. (int) $prodi);
        }

        if (is_numeric($konsentrasi = $this->state->get('filter.jurusan'))) {
            $query->where($db->quoteName('su.jurusan'). ' = '. (int) $konsentrasi);
        }

        if (is_numeric($role = $this->state->get('filter.role'))) {
            $query->where($db->quoteName('su.role'). ' = '. (int) $role);
        }

        $ordering = $this->state->get('list.ordering', 'u.name');
        $dir = $this->state->get('list.direction', 'ASC');

        $query->order($db->escape($ordering. ' '. $dir));

        return $query;
    }


    protected function getStoreId($id = '')
    {
        // Compile the store id.
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.prodi');
        $id .= ':' . $this->getState('filter.jurusan');
        $id .= ':' . $this->getState('filter.status');
        $id .= ':' . $this->getState('filter.role');

        return parent::getStoreId($id);
    }

    protected function populateState($ordering = 'u.name', $direction = 'asc')
    {
        $this->setState('filter.role', $this->getUserStateFromRequest($this->context.'.filter.role', 'filter_role'));
        $this->setState('filter.jurusan', $this->getUserStateFromRequest($this->context.'.filter.jurusan', 'filter_jurusan'));
        parent::populateState($ordering, $direction);
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

		// Include the plugins for the change of state event.
		\JPluginHelper::importPlugin($this->events_map['change_state']);

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

		$context = $this->option . '.' . $this->name;

		// Trigger the change state event.
		$result = $dispatcher->trigger($this->event_change_state, array($context, $pks, $value));

		if (in_array(false, $result, true))
		{
			$this->setError($table->getError());

			return false;
		}

		// Clear the component's cache
		$this->cleanCache();

		return true;
	}
}
