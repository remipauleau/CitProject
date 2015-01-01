<?php
namespace CitProject\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use CitProject\Model\Project;
use CitProject\Form\ProjectForm;
use CitProject\Form\ArchivedProjectForm;
use Zend\Session\Container;
use Zend\Http\Client;
use Zend\Http\Request;

class ProjectController extends AbstractActionController
{
   	protected $customerTable;
	protected $projectTable;
   	
   	public function indexAction()
    {
		// Get 1 page of active projects
		return $this->index('Active');
    }

    public function archivedAction()
    {
    	// Get 1 page of archived projects
		return $this->index('Archived');
    }

    protected function index($status)
    {
    	// Get 1 page of projects
    	$currentPage = $this->params()->fromQuery('page', 1);
    	$debut = $this->params()->fromQuery('debut', " ");
    	$tri = $this->params()->fromQuery('tri', NULL);
    	$desc = $this->params()->fromQuery('desc', NULL);
    	$adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
    	$select = $this->getProjectTable()->fetchAllPagination($status, $debut, $tri, $desc);
    	$paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\DbSelect($select, $adapter));
    	$paginator->setCurrentPageNumber($currentPage);
    	$paginator->setDefaultItemCountPerPage(20);
    	$messages = $paginator->getCurrentItems();
    
    	// Pass the project page to the view
    	return new ViewModel(array(
    			'debut' => $debut,
    			'tri' => $tri,
    			'desc' => $desc,
    			'paginator' => $paginator,
    	));
    }
    
    public function addAction()
    {
		// Create the form object
    	$form = new ProjectForm();
    	$form->addElements($this->getCustomerTable());
    	$form->get('submit')->setValue('Add');
    
		// Set the form filters and hydrate it with the data from the request
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$project = new Project();
    		$form->setInputFilter($project->getInputFilter());
     		$form->setData($request->getPost());

     		// Update the project object with the data from the valid form and create it into the database
    		if ($form->isValid()) {
    			$project->exchangeArray($form->getData());
        		$project->project_id = NULL;
    			$this->getProjectTable()->saveProject($project);
    
    			// Redirect to project list
    			return $this->redirect()->toRoute('project', array('action' => 'index'));
    		}
    	}
    	return array(
    		'form' => $form,
    	);
    }

    public function updateAction()
    {
    	return $this->_processUpdateForm(new ProjectForm());
    }

    public function activateAction()
    {
    	return $this->_processUpdateForm(new ArchivedProjectForm());
    }
    
    protected function _processUpdateForm($form)
    {
		// Check the presence of the id parameter for the project to update
    	$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {    		
    		return $this->redirect()->toRoute('project', array('action' => 'index'));
    	}
		// Create the project object and intialize it from the database 
    	$project = $this->getProjectTable()->getProject($id);

    	// Create the form object and initialize it from the existing project
    	$form->addElements($this->getCustomerTable());
    	$form->bind($project);
    	$form->get('submit')->setValue('Modifier');

    	// Set the form filters and hydrate it with the data from the request
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$form->setInputFilter($project->getInputFilter());
    		$form->setData($request->getPost());
    
     		// Update the project object with the data from the valid form and update it in the database
    		if ($form->isValid()) {
    			$this->getProjectTable()->saveProject($form->getData());
    
    			// Redirect to project list
    			return $this->redirect()->toRoute('project', array('action' => 'index'));
    		}
    	}
    	return array(
    		'form' => $form,
    		'id' => $id,
    	);
    }

    public function deleteAction()
    {
		// Check the presence of the id parameter for the project to update
    	$id = (int) $this->params()->fromRoute('id', 0);
    	if (!$id) {
    		return $this->redirect()->toRoute('project');
    	}
		// Retrieve the user validation from the post
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$del = $request->getPost('del', 'No');
    
			// And delete the project into the database in the "yes" case
    		if ($del == 'Yes') {
    			$id = (int) $request->getPost('id');
    			$this->getProjectTable()->deleteProject($id);
    		}
    
    		// Redirect to project list
    		return $this->redirect()->toRoute('project');
    	}
    
    	return array(
    		'id'    => $id,
    	);
    }

    public function getCustomerTable()
    {
    	if (!$this->customerTable) {
    		$sm = $this->getServiceLocator();
    		$this->customerTable = $sm->get('CitMasterData\Model\CustomerTable');
    	}
    	return $this->customerTable;
    }
    
    public function getProjectTable()
    {
    	if (!$this->projectTable) {
    		$sm = $this->getServiceLocator();
    		$this->projectTable = $sm->get('CitProject\Model\ProjectTable');
    	}
    	return $this->projectTable;
    }
}
