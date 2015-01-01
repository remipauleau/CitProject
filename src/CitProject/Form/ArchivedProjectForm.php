<?php
namespace CitProject\Form;

use Zend\Form\Form;

class ArchivedProjectForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('customer');
        $this->setAttribute('method', 'post');
    }
    
    public function addElements($customerTable)
    {
	   	// Populate the list-box from the active customer list
        $customers = array();
	    foreach($customerTable->fetchAllActive() as $customer) {
	    	$customers[$customer->customer_id] = $customer->name;
        }
        
        $this->add(
            array(
                'name' => 'status',
                'type' => 'Select',
                'attributes' => array(
                    'id'    => 'status'
                ),
                'options' => array(
                    'label' => 'Status:',
                    'value_options' => array(
                    		'Active' => 'Active',
                    		'Archived' => 'Archived',
					),
                ),
            )
        );

        $this->add(array(
        		'name' => 'submit',
        		'attributes' => array(
        				'type'  => 'submit',
        				'value' => 'OK',
        				'id' => 'submitbutton',
        		),
        ));

        // Champs cachés

        $this->add(array(
            'name' => 'customer_id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
	    
		$this->add(array(
        	'name' => 'name',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

        $this->add(array(
        	'name' => 'context',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

        $this->add(array(
        	'name' => 'objective',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

        $this->add(array(
        	'name' => 'begin_date',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

        $this->add(array(
        	'name' => 'expected_prod_date',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

        $this->add(array(
        	'name' => 'effective_prod_date',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        
        $this->add(array(
        	'name' => 'scope',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

        $this->add(array(
        	'name' => 'limits',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

        $this->add(array(
        	'name' => 'risk_analysis',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

        $this->add(array(
        	'name' => 'solution',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

        $this->add(
            array(
                'name' => 'csrf',
                'type' => 'Csrf',
                'options' => array(
                    'csrf_options' => array(
                        'timeout' => 600
                    )
                )
            )
        );
        
    	$this->add(array(
            'name' => 'project_id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
    }
}
