<?php
namespace CitProject\Model;

use Zend\Db\TableGateway\TableGateway;

class QuotationTable
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
    	if ($desc) $date = 'date DESC'; else $date = 'date ASC';
    	if ($tri) {
	    	if ($desc) $order = array($tri.' DESC', $date);
	    	else $order = array($tri.' ASC', $date);
    	}
		else $order = array($date);
    	$select = new \Zend\Db\Sql\Select();
    	$select->from($this->tableGateway->getTable())
    	->where(array('status' => $status))
    	->order($order);
    	return $select;
    }
   
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getQuotation($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('quotation_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveQuotation(Quotation $quotation)
    {
        $data = array(
            'quotation_id' => $quotation->quotation_id,
        	'project_id' => $quotation->project_id,
        	'status' => $quotation->status,
        	'date' => $quotation->date,
        	'dev-cost' => $quotation->dev_cost,
        	'analysis_cost' => $quotation->analysis_cost,
        	'func_valid_cost' => $quotation->func_valid_cost,
        	'tech_valid_cost' => $quotation->tech_valid_cost,
        	'production_cost' => $quotation->production_cost,
        	'vrs_cost' => $quotation->vrs_cost,
        	'managt_cost' => $quotation->managt_cost,
        );

        $id = (int)$quotation->quotation_id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getQuotation($id)) {
                $this->tableGateway->update($data, array('quotationt_id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteQuotation($id)
    {
        $this->tableGateway->delete(array('quotation_id' => $id));
    }
}
