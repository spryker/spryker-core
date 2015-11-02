<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Propel\Business\Model;

use Symfony\Component\Finder\SplFileInfo;

class PropelGroupedSchemaFinder implements PropelGroupedSchemaFinderInterface
{

    /**
     * @var array
     */
    protected $schemaFinder;

    /**
     * @param PropelSchemaFinderInterface $schemaFinder
     */
    public function __construct(PropelSchemaFinderInterface $schemaFinder)
    {
        $this->schemaFinder = $schemaFinder;
    }

    /**
     * @return array
     */
    public function getGroupedSchemaFiles()
    {
        $schemaFiles = [];
        foreach ($this->schemaFinder->getSchemaFiles() as $schemaFile) {
            $schemaFiles = $this->addSchemaToList($schemaFile, $schemaFiles);
        }

        return $schemaFiles;
    }

    /**
     * @param SplFileInfo $schemaFile
     * @param array $schemaFiles
     *
     * @return array
     */
    private function addSchemaToList(SplFileInfo $schemaFile, array $schemaFiles)
    {
        $fileIdentifier = $schemaFile->getFilename();
        if (!isset($schemaFiles[$fileIdentifier])) {
            $schemaFiles[$fileIdentifier] = [];
        }
        $schemaFiles[$fileIdentifier][] = $schemaFile;

        return $schemaFiles;
    }

}
