<?php
namespace CitProject;

return array(
    'controllers' => array(
        'invokables' => array(
        	'CitProject\Controller\PlanningEvent' => 'CitProject\Controller\PlanningEventController',
        	'CitProject\Controller\Project' => 'CitProject\Controller\ProjectController',
        	'CitProject\Controller\Quotation' => 'CitProject\Controller\QuotationController',
        	'CitProject\Controller\WorkUnit' => 'CitProject\Controller\WorkUnitController',
        ),
    ),
 
    'router' => array(
        'routes' => array(
            'index' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'CitProject\Controller\Project',
                        'action'     => 'index',
                    ),
                ),
            ),
            'planningEvent' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/planning-event[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'CitProject\Controller\PlanningEvent',
                        'action'     => 'index',
                    ),
                ),
            ),
            'project' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/project[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'CitProject\Controller\Project',
                        'action'     => 'index',
                    ),
                ),
            ),
            'quotation' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/quotation[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'CitProject\Controller\quotation',
                        'action'     => 'index',
                    ),
                ),
            ),
            'workUnit' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/work-unit[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'CitProject\Controller\WorkUnit',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
		
    'view_manager' => array(
    	'strategies' => array(
    			'ViewJsonStrategy',
    	),
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',       // On défini notre doctype
        'not_found_template'       => 'error/404',   // On indique la page 404
        'exception_template'       => 'error/index', // On indique la page en cas d'exception
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
        ),
        'template_path_stack' => array(
            'master' => __DIR__ . '/../view',
        ),
    ),
);
