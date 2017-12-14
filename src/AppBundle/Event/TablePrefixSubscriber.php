<?php
/**
 * Created by PhpStorm.
 * User: mbela
 * Date: 13/05/2017
 * Time: 13:08
 */

namespace AppBundle\Event;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;

class TablePrefixSubscriber implements EventSubscriber{

    private $prefix;

    /**
     * TablePrefixSubscriber constructor.
     * @param $prefix
     */
    public function __construct($prefix)
    {
        $this->prefix = $prefix;
    }


    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array('loadClassMetaData');
    }

    public function loadClassMetaData(LoadClassMetadataEventArgs $args){
        /**
         * @var ClassMetadata $metadata
         */
        $metadata = $args->getClassMetadata();

        //Single table inheritance
        if($metadata->isInheritanceTypeSingleTable() && !$metadata->isRootEntity()){
            return;
        }

        $metadata->setTableName($this-> prefix . $metadata->getTableName());

        // TODO : change table name in many to many relationships
    }
}