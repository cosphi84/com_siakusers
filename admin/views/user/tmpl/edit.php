<?php
defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

HTMLHelper::_('behavior.formvalidator');
HTMLHelper::_('formbehavior.chosen','select');
HTMLHelper::_('jquery.framework');
HTMLHelper::_('script', 'com_siakuser/jurusan.js', array('version'=>'auto', 'relative'=>true));

?>

<form method="POST" id="adminForm" name="adminForm" class="form-validate"
action="index.php?option=com_siakuser&view=user&layout=edit&id=<?php echo $this->item->id; ?>">
    <div class="form-horizontal">
    <?php echo HTMLHelper::_('bootstrap.startTabSet', 'myTab', array('active'=>'akademik')); ?>

    <?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'akademik', Text::_('COM_SIAKUSER_TAB_HEADING_AKADEMIK_LABEL')); ?>
	<?php echo $this->form->renderFieldset('user_akademik'); ?>
	<?php echo HTMLHelper::_('bootstrap.endTab'); ?>

    <?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'sidang', Text::_('COM_SIAKUSER_TAB_HEADING_DETAILS_LABEL')); ?>
	<?php echo $this->form->renderFieldset('user_details'); ?>
	<?php echo HTMLHelper::_('bootstrap.endTab'); ?>

    <?php echo JHtml::_('bootstrap.endTabSet'); ?>
    </div>
    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
</form>
