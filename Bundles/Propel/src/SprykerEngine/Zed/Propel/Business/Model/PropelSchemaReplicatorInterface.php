<?php

namespace SprykerEngine\Zed\Propel\Business\Model;

interface PropelSchemaReplicatorInterface
{

    /**
     * @param PropelSchemaFinderInterface $schemaFinder
     */
    public function replicateSchemaFiles(PropelSchemaFinderInterface $schemaFinder);

}
