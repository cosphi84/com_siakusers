<?php
/**
 * View class User
 */

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;



class SiakuserViewUser extends HtmlView
{
    protected $form;
    protected $item;

    public function display($tpl = null)
    {
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');

        if (count($errors = $this->get('Errors'))) {
            throw new Exception(implode('\n', $errors), 500);
            return false;
        }

        Factory::getApplication()->input->set('hidemainmenu', 1);
        $this->drawToolbar();
        parent::display($tpl);
    }

    protected function drawToolbar()
    {
        $canDo = ContentHelper::getActions('com_siakuser');
        $title = Text::sprintf('COM_SIAKUSER_USER_EDIT_PAGE_TITLE', $this->item->user);

        ToolbarHelper::title($title);

        if($canDo->get('core.create')){
            ToolbarHelper::apply('user.apply');
            ToolbarHelper::save('user.save');
        }

        
        ToolbarHelper::cancel('user.cancel', 'JTOOLBAR_CLOSE');
        
    }
}