<?php
namespace OmekaTest\Model;

use OmekaTest\Bootstrap;
use Omeka\Model\Site;
use Omeka\Test\ModelTest;

class SiteTest extends ModelTest
{
    public $em;
    protected $className = 'Omeka\\Model\\Site';
    
    protected function updateNewEntity($entity) {}
}