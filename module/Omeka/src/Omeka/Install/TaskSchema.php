<?php
namespace Omeka\Install;

use Omeka\Install\TaskAbstract;
use Omeka\Install\TaskInterface;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\ServiceLocatorInterface;

class TaskSchema extends TaskAbstract implements TaskInterface
{
    protected $taskName = "Install tables";
    
    public function perform()
    {
        $conn = $this->getServiceLocator()->get('EntityManager')->getConnection();
        $config = $this->getServiceLocator()->get('ApplicationConfig');
        
        //check if tables already exist
        $tables = $conn->getSchemaManager()->listTableNames();
        if(!empty($tables)) {
            $this->result->addMessage('Omeka is already installed.', 'ERROR');
            $this->result->setSuccess(false);
            return;
        }

        if(!is_readable( __DIR__ . '/install_data/schema.txt')) {
            $this->result->addMessage('Could not read the schema installation file.', 'ERROR');
            //_log($e);
            $this->result->setSuccess(false);
            return;   
        }
        $classes = unserialize(file_get_contents( __DIR__ . '/install_data/schema.txt'));
        if(!is_array($classes)) {
            $this->result->addMessage('Could not read the schema installation file.', 'ERROR');
            //_log($e);
            $this->result->setSuccess(false);
            return;            
        }
        
        //dbExport slaps 'DBPREFIX_' as the prefix onto all classes, so do the replace here for the real prefix
        foreach($classes as $index=>$sql) {
            $classes[$index] = str_replace('DBPREFIX_', $config['entity_manager']['table_prefix'], $classes[$index]);
        }

        foreach ($classes as $sql) {
            try {
                $conn->executeQuery($sql);                    
            } catch(\Doctrine\DBAL\DBALException $e) {
                $this->result->addExceptionMessage($e, 'A problem occurred while creating tables.');
                //_log($e);
                $this->result->setSuccess(false);
            }
        }       
        $this->result->addMessage('Tables installed ok.'); 
        $this->result->setSuccess(true);
    }
}