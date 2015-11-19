<?php
namespace User;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

use User\Model\User;
use User\Model\UserTable;

class Module implements AutoloaderProviderInterface {

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php'
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/', __NAMESPACE__)
                )
            )
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        $e->getApplication()
            ->getServiceManager()
            ->get('translator');
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getServiceConfig() {
        return array (
    			'factories' => array (
    					'User\Model\UserTable' => function ($sm) {
    						$tableGateway = $sm->get( 'UserTableGateway' );
    						$table = new UserTable( $tableGateway );
    						return $table;
    					},
    					'UserTableGateway' => function ($sm) {
    						$dbAdapter = $sm->get( 'Zend\Db\Adapter\Adapter' );
    						$resultSetPrototype = new ResultSet ();
    						$resultSetPrototype->setArrayObjectPrototype ( new User () );
    						return new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
    					},
    			)
    	);
    }
}