<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model;

use Symfony\Component\Finder\SplFileInfo;

class PropelGroupedSchemaFinder implements PropelGroupedSchemaFinderInterface
{

    /**
     * @var \Spryker\Zed\Propel\Business\Model\PropelSchemaFinderInterface
     */
    protected $schemaFinder;

    /**
     * @param \Spryker\Zed\Propel\Business\Model\PropelSchemaFinderInterface $schemaFinder
     */
    public function __construct(PropelSchemaFinderInterface $schemaFinder)
    {
        $this->schemaFinder = $schemaFinder;
    }

    /**
     * @return array|\Symfony\Component\Finder\SplFileInfo
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
     * @param \Symfony\Component\Finder\SplFileInfo $schemaFile
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
