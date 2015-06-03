<?php

namespace SprykerEngine\Zed\Propel\Business\Model;

use SprykerEngine\Zed\Propel\Business\Model\DirectoryRemoverInterface;

class PropelSchema implements PropelSchemaInterface
{

    /**
     * @var DirectoryRemoverInterface
     */
    protected $directoryRemover;

    /**
     * @var PropelSchemaFinder
     */
    protected $schemaFinder;

    /**
     * @var PropelSchemaReplicator
     */
    protected $schemaReplicator;

    /**
     * @param DirectoryRemoverInterface $directoryRemover
     * @param PropelSchemaFinderInterface $schemaFinder
     * @param PropelSchemaReplicatorInterface $schemaReplicator
     */
    public function __construct(
        DirectoryRemoverInterface $directoryRemover,
        PropelSchemaFinderInterface $schemaFinder,
        PropelSchemaReplicatorInterface $schemaReplicator
    )
    {
        $this->directoryRemover = $directoryRemover;
        $this->schemaFinder = $schemaFinder;
        $this->schemaReplicator = $schemaReplicator;
    }

    public function cleanTargetDirectory()
    {
        $this->directoryRemover->execute();
    }

    public function copyToTargetDirectory()
    {
        $this->schemaReplicator->replicateSchemaFiles(
            $this->schemaFinder
        );
    }

}
