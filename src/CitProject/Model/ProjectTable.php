<?php
namespace CitProject\Model;

use Zend\Db\TableGateway\TableGateway;

class ProjectTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    
    public function getAdapter()
    {
    	return $this->tableGateway->getAdapter();
    }

    public function fetchAllPagination($status, $debut, $tri, $desc)
    {
    	if ($desc) $begin_date = 'begin_date DESC'; else $begin_date = 'begin_date ASC';
    	if ($desc) $name = 'name DESC'; else $name = 'name ASC';
    	if ($tri) {
	    	if ($desc) $order = array($tri.' DESC', $begin_date, $name);
	    	else $order = array($tri.' ASC', $begin_date, $name);
    	}
		else $order = array($begin_date, $name);
    	$select = new \Zend\Db\Sql\Select();
    	$select->from($this->tableGateway->getTable())
    	->where(array('status' => $status, 'name >= ?' => $debut))
    	->order($order);    
    	return $select;
    }
   
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function fetchAllActive()
    {
    	$select = new \Zend\Db\Sql\Select();
    	$select->from($this->tableGateway->getTable())
    	->where(array('status' => 'Active'))
    	->order('name');
    	$resultSet = $this->tableGateway->selectWith($select);
    	return $resultSet;
    }
    
    public function getProject($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('project_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveProject(Project $project)
    {
        $data = array(
            'project_id' => $project->project_id,
            'customer_id' => $project->customer_id,
        	'name' => $project->name,
        	'status' => $project->status,
        	'context' => $project->context,
        	'objective' => $project->objective,
        	'begin_date' => $project->begin_date,
        	'expected_prod_date' => $project->expected_prod_date,
        	'effective_prod_date' => $project->effective_prod_date,
        	'scope' => $project->scope,
        	'limits' => $project->limits,
        	'risk_analysis' => $project->risk_analysis,
        	'solution' => $project->solution,
        );

        $id = (int)$project->project_id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getProject($id)) {
                $this->tableGateway->update($data, array('project_id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteProject($id)
    {
        $this->tableGateway->delete(array('project_id' => $id));
    }
}
