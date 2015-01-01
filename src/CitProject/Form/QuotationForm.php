<?php
namespace CitProject\Form;

use Zend\Form\Form;

class QuotationForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('quotation');
        $this->setAttribute('method', 'post');
    }
    
    public function addElements($projectTable)
    {
	   	// Populate the list-box from the active customer list
        $projects = array();
	    foreach($projectTable->fetchAllActive() as $project) {
	    	$projects[$project->project_id] = $project->name;
        }
	    $this->add(array(
            'name' => 'project_id',
            'type'  => 'Select',
        	'attributes' => array(
            	'id'    => 'project_id'
        	),
            'options' => array(
                'label' => 'Project:',
                'value_options' => $projects,
                'empty_option'  => '--- Select a project ---'
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

        $this->add(
        		array(
        				'type' => 'Zend\Form\Element\Date',
        				'name' => 'date',
        				'options' => array(
        						'label' => 'Quotation date:'
        				),
        				'attributes' => array(
        						'min' => '2000-01-01',
        						'max' => '2030-01-01',
        						'step' => '1', // days; default step interval is 1 day
        				)
        		)
        );

        $this->add(array(
        		'name' => 'dev_cost',
        		'attributes' => array(
        				'type'  => 'text',
        				'size'  => '20',
        		),
        		'options' => array(
        				'label' => 'Development cost:',
        		),
        ));

        $this->add(array(
        		'name' => 'analysis_cost',
        		'attributes' => array(
        				'type'  => 'text',
        				'size'  => '20',
        		),
        		'options' => array(
        				'label' => 'Analysis cost:',
        		),
        ));

        $this->add(array(
        		'name' => 'func_valid_cost',
        		'attributes' => array(
        				'type'  => 'text',
        				'size'  => '20',
        		),
        		'options' => array(
        				'label' => 'Functional validation cost:',
        		),
        ));

        $this->add(array(
        		'name' => 'tech_valid_cost',
        		'attributes' => array(
        				'type'  => 'text',
        				'size'  => '20',
        		),
        		'options' => array(
        				'label' => 'Technical validation cost:',
        		),
        ));

        $this->add(array(
        		'name' => 'production_cost',
        		'attributes' => array(
        				'type'  => 'text',
        				'size'  => '20',
        		),
        		'options' => array(
        				'label' => 'Production cost:',
        		),
        ));

        $this->add(array(
        		'name' => 'vrs_cost',
        		'attributes' => array(
        				'type'  => 'text',
        				'size'  => '20',
        		),
        		'options' => array(
        				'label' => 'Validation of regular service cost:',
        		),
        ));

        $this->add(array(
        		'name' => 'managt_cost',
        		'attributes' => array(
        				'type'  => 'text',
        				'size'  => '20',
        		),
        		'options' => array(
        				'label' => 'Management cost:',
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
