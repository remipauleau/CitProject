<?php
namespace CitProject\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use CitProject\Model\Quotation;
use CitProject\Form\QuotationForm;
use Zend\Session\Container;
use Zend\Http\Client;
use Zend\Http\Request;

class QuotationController extends AbstractActionController
{
	protected $projectTable;
	protected $quotationTable;
	
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
    	// Get 1 page of quotations
    	$currentPage = $this->params()->fromQuery('page', 1);
    	$debut = $this->params()->fromQuery('debut', " ");
    	$tri = $this->params()->fromQuery('tri', NULL);
    	$desc = $this->params()->fromQuery('desc', NULL);
    	$adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
    	$select = $this->getQuotationTable()->fetchAllPagination($status, $debut, $tri, $desc);
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
    	$form = new QUotationForm();
    	$form->addElements($this->getProjectTable());
    	$form->get('submit')->setValue('Add');
    
		// Set the form filters and hydrate it with the data from the request
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$quotation = new Quotation();
    		$form->setInputFilter($quotation->getInputFilter());
     		$form->setData($request->getPost());

     		// Update the quotation object with the data from the valid form and create it into the database
    		if ($form->isValid()) {
    			$project->exchangeArray($form->getData());
        		$quotation->quotation_id = NULL;
    			$this->getQuotationTable()->saveQuotation($quotation);
    
    			// Redirect to quotation list
    			return $this->redirect()->toRoute('quotation', array('action' => 'index'));
    		}
    	}
    	return array(
    		'form' => $form,
    	);
    }

    public function updateAction()
    {
    	return $this->_processUpdateForm(new QuotationForm());
    }

    public function activateAction()
    {
    	return $this->_processUpdateForm(new ArchivedQUotationForm());
    }
    
    protected function _processUpdateForm($form)
    {
		// Check the presence of the id parameter for the quotation to update
    	$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {    		
    		return $this->redirect()->toRoute('quotation', array('action' => 'index'));
    	}
		// Create the quotation object and intialize it from the database 
    	$quotation = $this->getQuotationTable()->getQuotation($id);

    	// Create the form object and initialize it from the existing quotation
    	$form->addElements($this->getQuotationTable());
    	$form->bind($quotation);
    	$form->get('submit')->setValue('Modifier');

    	// Set the form filters and hydrate it with the data from the request
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$form->setInputFilter($project->getInputFilter());
    		$form->setData($request->getPost());
    
     		// Update the quotation object with the data from the valid form and update it in the database
    		if ($form->isValid()) {
    			$this->getQuotationTable()->saveQuotation($form->getData());
    
    			// Redirect to quotation list
    			return $this->redirect()->toRoute('quotation', array('action' => 'index'));
    		}
    	}
    	return array(
    		'form' => $form,
    		'id' => $id,
    	);
    }

    public function deleteAction()
    {
		// Check the presence of the id parameter for the quotation to update
    	$id = (int) $this->params()->fromRoute('id', 0);
    	if (!$id) {
    		return $this->redirect()->toRoute('quotation');
    	}
		// Retrieve the user validation from the post
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$del = $request->getPost('del', 'No');
    
			// And delete the quotation into the database in the "yes" case
    		if ($del == 'Yes') {
    			$id = (int) $request->getPost('id');
    			$this->getQuotationTable()->deleteQuotation($id);
    		}
    
    		// Redirect to quotation list
    		return $this->redirect()->toRoute('quotation');
    	}
    
    	return array(
    		'id'    => $id,
    	);
    }

    public function getProjectTable()
    {
    	if (!$this->projectTable) {
    		$sm = $this->getServiceLocator();
    		$this->projectTable = $sm->get('CitProject\Model\ProjectTable');
    	}
    	return $this->projectTable;
    }
    
    public function getQuotationTable()
    {
    	if (!$this->quotationTable) {
    		$sm = $this->getServiceLocator();
    		$this->quotationTable = $sm->get('CitProject\Model\QuotationTable');
    	}
    	return $this->quotationTable;
    }
}
