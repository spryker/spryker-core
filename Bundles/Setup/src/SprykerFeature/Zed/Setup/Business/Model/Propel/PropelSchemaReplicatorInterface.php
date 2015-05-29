<?php

namespace SprykerFeature\Zed\Setup\Business\Model\Propel;

use Symfony\Component\Filesystem\Filesystem;

interface PropelSchemaReplicatorInterface
{

    /**
     * @param PropelSchemaFinderInterface $schemaFinder
     */
    public function replicateSchemaFiles(PropelSchemaFinderInterface $schemaFinder);

}
