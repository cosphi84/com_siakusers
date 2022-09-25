<?php

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

class SiakuserViewUsers extends HtmlView
{
    public $filterForm;
    public $activeFilters;
    protected $items;
    protected $pagination;
    protected $state;
    protected $canDo;

    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');
        $this->activeFilters = $this->get('ActiveFilters');
        $this->filterForm = $this->get('FilterForm');
        $this->canDo = ContentHelper::getActions('com_siakuser');

        if(count($errors = $this->get('Errors')))
        {
            throw new Exception(implode("<br />", $errors), 500);
            return false;
        }

        SiakuserHelper::submenu('users');
        $this->toolbar();

        parent::display($tpl);

    }

    protected function toolbar()
    {
        $canDo = $this->canDo;
        ToolbarHelper::title(Text::_('COM_SIAKUSER_USERS_PAGE_TITLE'));

        if($canDo->get('core.edit') || $canDo->get('core.edit.state'))
        {
            ToolbarHelper::editList('user.edit');
            //ToolbarHelper::custom('users.block', 'ban-circle', 'ban-circle', Text::_('COM_SIAKUSER_TOOLBAR_BLOCK'));
            ToolbarHelper::custom('users.lock', 'lock', 'lock', Text::_('COM_SIAKUSER_TOOLBAR_LOCK'));
            ToolbarHelper::custom('users.unlock', 'unlock', 'unlock', Text::_('COM_SIAKUSER_TOOLBAR_UNLOCK'));
            ToolbarHelper::custom('users.lulus', 'flag', 'flag', Text::_('COM_SIAKUSER_TOOLBAR_LULUS'));
        }
        ToolbarHelper::divider();

        if($canDo->get('core.admin') || $canDo->get('core.option')) {
            ToolbarHelper::preferences('com_siakuser');
        }
    }
}