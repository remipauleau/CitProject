<?php
namespace CitProject\Form;

use Zend\Form\Form;

class ProjectForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('project');
        $this->setAttribute('method', 'post');
    }
    
    public function addElements($customerTable)
    {
	   	// Populate the list-box from the active customer list
        $customers = array();
	    foreach($customerTable->fetchAllActive() as $customer) {
	    	$customers[$customer->customer_id] = $customer->name;
        }
	    $this->add(array(
            'name' => 'customer_id',
            'type'  => 'Select',
        	'attributes' => array(
            	'id'    => 'customer_id'
        	),
            'options' => array(
                'label' => 'Customer:',
                'value_options' => $customers,
                'empty_option'  => '--- Select a customer ---'
            ),
        ));
	    
		$this->add(array(
        		'name' => 'name',
        		'attributes' => array(
        				'type'  => 'text',
        				'size'  => '100',
        		),
        		'options' => array(
        				'label' => 'Project name:',
        		),
        ));
        
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
        		'name' => 'context',
        		'type'  => 'textarea',
        		'attributes' => array(
        				'rows' => 5,
        				'cols' => 100,
        				'placeholder' => 'Precise the stakes of the project from the customer point of view'
        		),
        		'options' => array(
        				'label' => 'Project context:',
        		),
        ));

        $this->add(array(
        		'name' => 'objective',
        		'type'  => 'textarea',
        		'attributes' => array(
        				'rows' => 5,
        				'cols' => 100,
        		),
        		'options' => array(
        				'label' => 'Objective:',
        		),
        ));

        $this->add(
        		array(
        				'type' => 'Zend\Form\Element\Date',
        				'name' => 'begin_date',
        				'options' => array(
        						'label' => 'Project begin date:'
        				),
        				'attributes' => array(
        						'min' => '2010-01-01',
        						'max' => '2030-01-01',
        						'step' => '1', // days; default step interval is 1 day
        				)
        		)
        );

        $this->add(
        		array(
        				'type' => 'Zend\Form\Element\Date',
        				'name' => 'expected_prod_date',
        				'options' => array(
        						'label' => 'Expected production date:'
        				),
        				'attributes' => array(
        						'min' => '2010-01-01',
        						'max' => '2030-01-01',
        						'step' => '1', // days; default step interval is 1 day
        				)
        		)
        );

        $this->add(
        		array(
        				'type' => 'Zend\Form\Element\Date',
        				'name' => 'effective_prod_date',
        				'options' => array(
        						'label' => 'Effective production date:'
        				),
        				'attributes' => array(
        						'min' => '2010-01-01',
        						'max' => '2030-01-01',
        						'step' => '1', // days; default step interval is 1 day
        				)
        		)
        );
        
        $this->add(array(
        		'name' => 'scope',
        		'type'  => 'textarea',
        		'attributes' => array(
        				'rows' => 5,
        				'cols' => 100,
        		),
        		'options' => array(
        				'label' => 'Scope:',
        		),
        ));

        $this->add(array(
        		'name' => 'limits',
        		'type'  => 'textarea',
        		'attributes' => array(
        				'rows' => 5,
        				'cols' => 100,
        		),
        		'options' => array(
        				'label' => 'Limits:',
        		),
        ));

        $this->add(array(
        		'name' => 'risk_analysis',
        		'type'  => 'textarea',
        		'attributes' => array(
        				'rows' => 5,
        				'cols' => 100,
        		),
        		'options' => array(
        				'label' => 'Risk analysis:',
        		),
        ));

        $this->add(array(
        		'name' => 'solution',
        		'type'  => 'textarea',
        		'attributes' => array(
        				'rows' => 5,
        				'cols' => 100,
        		),
        		'options' => array(
        				'label' => 'Solution:',
        		),
        ));
        
        $this->add(array(
			'name' => 'submit',
 			'attributes' => array(
				'type'  => 'submit',
				'value' => 'OK',
				'id' => 'submitbutton',
			),
		));
        
        // Champs cachés
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
