<?php


defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormHelper;


FormHelper::loadFieldClass('list');

class JFormFieldProdi extends JFormFieldList
{
    protected $type = 'Prodi';
    protected $loadExternally = 0;

    public function getOptions()
    {
        $db = Factory::getDbo();
        $query = $db->getQuery(true);

        $query->select(array('id AS value', 'title AS text'))
            ->from($db->quoteName('#__siak_prodi'))
            ->where($db->quoteName('state'). ' = '. 1)
            ->order($db->quoteName('id'). ' ASC');
        
        $db->setQuery($query);
        try {
            $result = $db->loadObjectList();
        } catch (RuntimeException $e) {
            throw new Exception('Error load Prodi : '.$e->getMessage());
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