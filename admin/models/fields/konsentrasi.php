<?php


defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormHelper;


FormHelper::loadFieldClass('list');

class JFormFieldKonsentrasi extends JFormFieldList
{
    protected $type = 'Konsentrasi';
    protected $loadExternally = 0;

    public function getOptions()
    {
        $db = Factory::getDbo();
        $query = $db->getQuery(true);
        $app = Factory::getApplication();
        $prodi = $app->getUserStateFromRequest('com_siakuser.filter.prodi', 'filter_prodi', null);
        
        $query->select(array('a.id AS value', 'a.title AS text'))
            ->from($db->quoteName('#__siak_jurusan', 'a'))
            ->where($db->quoteName('a.state'). ' = '. 1);
            if($prodi > 0)
            {
                $query->where($db->quoteName('a.prodi'). ' = '. (int) $prodi);
            }
            $query->order('a.prodi, a.title ASC');
       
           
            
        $db->setQuery($query);
        try {
            $result = $db->loadObjectList();
        } catch (RuntimeException $e) {
            throw new Exception('Error load konsentrasi : '.$e->getMessage());
        }
        
        foreach ($result as $k => $v) {
            $opts[] = JHtmlSelect::option($v->value, $v->text);
        }

        if (!$this->loadExternally) {
           $opts = array_merge(parent::getOptions(), $opts);
        }

        return $opts;
    }

    public function getOptionsExternally()
    {
        $this->loadExternally = 1;

        return $this->getOptions();
    }
}