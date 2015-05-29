<?php

namespace SprykerFeature\Zed\Setup\Business\Model\Propel;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\SplFileInfo;

class PropelSchemaReplicator implements PropelSchemaReplicatorInterface
{

    /**
     * @var string
     */
    protected $targetDirectory;

    /**
     * @param $targetDirectory
     */
    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * @param PropelSchemaFinderInterface $schemaFinder
     */
    public function replicateSchemaFiles(PropelSchemaFinderInterface $schemaFinder)
    {
        $fileSystem = new Filesystem();

        foreach ($schemaFinder->getSchemaFiles() as $schemaFile) {
            $fileSystem->copy(
                $schemaFile->getPathname(),
                $this->getTargetFileName($schemaFile)
            );
        }
    }

    /**
     * @param SplFileInfo $schemaFile
     *
     * @return string
     */
    private function getTargetFileName(SplFileInfo $schemaFile)
    {
        return $this->targetDirectory . DIRECTORY_SEPARATOR . $schemaFile->getFilename();
    }

}
