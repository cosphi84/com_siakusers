<?php

/**
 * SIAK User Controller class
 */
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\Utilities\ArrayHelper;

class SiakuserControllerUsers extends AdminController
{

    public function __construct($config = array(), MVCFactoryInterface $factory = null)
	{
		parent::__construct($config, $factory);

		// Define standard task mappings.

		// Value = 0
		

		// Value = 2
		$this->registerTask('lock', 'doJob');

		// Value = -2
		$this->registerTask('unlock', 'doJob');

		// Value = -3
		$this->registerTask('lulus', 'doJob');
		
	}

    public function getModel($name = 'User', $prefix = 'SiakuserModel', $config = array())
    {
        return parent::getModel($name, $prefix, $config);
    }


    public function doJob()
	{
		// Check for request forgeries
		$this->checkToken();

		// Get items to publish from the request.
		$cid = (array) $this->input->get('cid', array(), 'int');
		$data = array('lock' => 0, 'unlock' => 1, 'lulus' => 2);
		$task = $this->getTask();
		$value = ArrayHelper::getValue($data, $task, 0, 'int');
        

		// Remove zero values resulting from input filter
		$cid = array_filter($cid);

		if (empty($cid))
		{
			\JLog::add(\JText::_($this->text_prefix . '_NO_ITEM_SELECTED'), \JLog::WARNING, 'jerror');
		}
		else
		{
			// Get the model.
			$model = $this->getModel();
           

			// Publish the items.
			try
			{
				$model->publish($cid, $value, $task);
				$errors = $model->getErrors();
				$ntext = "%d Done";

				
                if ($errors)
                {
                    \JFactory::getApplication()->enqueueMessage(\JText::plural('%d', count($cid)), 'error');
                }
                else
                {
                    $this->setMessage(\JText::plural($ntext, count($cid)));
                }
				
			}
			catch (\Exception $e)
			{
				$this->setMessage($e->getMessage(), 'error');
			}
		}

		$extension = $this->input->get('extension');
		$extensionURL = $extension ? '&extension=' . $extension : '';
		$this->setRedirect(\JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list . $extensionURL, false));
	}
}
