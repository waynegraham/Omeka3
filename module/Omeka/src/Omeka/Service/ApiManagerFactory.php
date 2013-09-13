<?php
namespace Omeka\Service;

use Omeka\Api\Manager as ApiManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * API manager factory.
 */
class ApiManagerFactory implements FactoryInterface
{
    /**
     * Create the API manager service.
     * 
     * @param ServiceLocatorInterface $serviceLocator
     * @return ApiManager
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $resources = $config['api_manager']['resources'];
        
        $apiManager = new ApiManager;
        $apiManager->registerResources($resources);
        $apiManager->setServiceLocator($serviceLocator);
        return $apiManager;
    }
}