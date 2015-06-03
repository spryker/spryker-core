<?php

namespace SprykerEngine\Zed\Propel\Business\Model;

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

        //TODO HAAAAACK, remove this when Schema merging works ...
        //GOD this looks ugly, but there is no point making it pretty because it will be obsolete
        //with schema merging
        $targetFileMap = [];

        foreach ($schemaFinder->getSchemaFiles() as $schemaFile) {
            $fileName = $this->getTargetFileName($schemaFile);
            $bundleName = $this->getBundleNameFromSchemaFile($schemaFile);

            if (!isset($targetFileMap[$bundleName])) {
                $targetFileMap[$bundleName] = true;
                $fileSystem->copy(
                    $schemaFile->getPathname(),
                    $this->getTargetFileName($schemaFile)
                );
            }
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

    /**
     * @param SplFileInfo $schemaFile
     *
     * @return string
     */
    private function getBundleNameFromSchemaFile(SplFileInfo $schemaFile)
    {
        return str_replace(['spy_', 'kam_', '.schema.xml'], '', $schemaFile->getFilename());
    }

}
