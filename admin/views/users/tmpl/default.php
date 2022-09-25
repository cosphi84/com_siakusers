<?php

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

HTMLHelper::_('behavior.multiselect');
HTMLHelper::_('formbehavior.chosen', 'select');


$listOrder = $this->escape($this->state->get('list.ordering'));
$listDir   = $this->escape($this->state->get('list.direction'));

?>
<form method="POST" name="adminForm" id="adminForm" action="index.php?option=com_siakuser&view=users">
	<div id="j-sidebar-container" class="span2">
		<?php echo JHtmlSidebar::render(); ?>
	</div>
	<div id="j-main-container" class="span10">
		<div class="row-fluid">
			<div class="span12">
				<?php echo LayoutHelper::render('joomla.searchtools.default', ['view' => $this]); ?>
			</div>
		</div>

		<?php if(empty($this->items)) : ?>
		<div class="alert alert-no-items">
			<?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
		</div>
		<?php else : ?>
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th class="center" width="1%">
						<?php echo HTMLHelper::_('grid.checkall'); ?>
					</th>
					<th class="center">
						<?php echo HTMLHelper::_('searchtools.sort', 'COM_SIAKUSER_HEADING_USER_LABEL', 'u.name', $listDir, $listOrder); ?>
					</th>
					<th class="center">
						<?php echo  HTMLHelper::_('searchtools.sort', 'COM_SIAKUSER_HEADING_STATUS_LABEL', 'su.status', $listDir, $listOrder); ?>
					</th>
					<th class="center">
						<?php echo HTMLHelper::_('searchtools.sort', 'COM_SIAKUSER_HEADING_TRANSAKSI_LABEL', 'su.aktif', $listDir, $listOrder); ?>
					</th>
					<th class="center nowrap">
						<?php echo HTMLHelper::_('searchtools.sort', 'COM_SIAKUSER_HEADING_RESET_LABEL', 'su.reset', $listDir, $listOrder); ?>
					</th>
					<th class="center">
						<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'su.id', $listDir, $listOrder); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="5" class="center">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>

			<body>
				<?php foreach($this->items as $k=>$item) : ?>
				<tr class="row<?php echo $k % 2; ?>">
					<td>
						<?php echo HTMLHelper::_('grid.id', $k, $item->id); ?>
					</td>
					<td>
						<strong><?php echo $this->escape($item->name); ?></strong>
						<div class="small">
							<?php if($item->role == 0) : ?>
							<?php echo $item->programStudi.' ('.$item->konsentrasi.') '. $item->angkatan. ', '. $item->kelasMhs . ':'; ?>
							<?php if($item->tipe_user == 1) {
							    echo "Reguler";
							} else {
							    echo "Pindahan";
							} ?>
							<?php else : ?>
							<?php echo 'NIK :'. $item->nik .', NIDN :'. $item->nidn; ?>
							<?php endif; ?>
						</div>
					</td>
					<td class="center">
						<?php
							        switch ($item->status) {
							            case '-1':
							                echo 'Keluar';
							                break;

							            case '0':
							                echo 'Tidak Aktif';
							                break;

							            case '2':
							                echo 'Lulus';
							                break;

							            default:
							                echo 'Aktif';
							                break;
							        }
				    ?>
					</td>
					<td class="center">
						<?php if($item->aktif == 1) : ?>
						<?php echo Text::_('JYES'); ?>
						<?php else: ?>
						<?php echo Text::_('JNO'); ?>
						<?php endif; ?>
					</td>
					<td class="center">
						<?php if($item->reset == 1) : ?>
						<?php echo Text::_('JYES'); ?>
						<?php else: ?>
						<?php echo Text::_('JNO'); ?>
						<?php endif; ?>
					</td>
					<td class="center">
						<?php echo (int) $item->id; ?>
					</td>
				</tr>
				<?php endforeach; ?>
				</tbody>
		</table>
		<?php endif; ?>
	</div>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo HTMLHelper::_('form.token'); ?>
</form>