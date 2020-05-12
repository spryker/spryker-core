<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\ConfigReader;

use Spryker\Zed\Propel\Dependency\External\PropelToFilesystemAdapterInterface;
use Spryker\Zed\Propel\PropelConfig;

class PropelConfigReader implements PropelConfigReaderInterface
{
    /**
     * @var \Spryker\Zed\Propel\PropelConfig
     */
    protected $propelConfig;

    /**
     * @var \Spryker\Zed\Propel\Dependency\External\PropelToFilesystemAdapterInterface
     */
    protected $fileSystem;

    /**
     * @param \Spryker\Zed\Propel\PropelConfig $propelConfig
     * @param \Spryker\Zed\Propel\Dependency\External\PropelToFilesystemAdapterInterface $fileSystem
     */
    public function __construct(
        PropelConfig $propelConfig,
        PropelToFilesystemAdapterInterface $fileSystem
    ) {
        $this->propelConfig = $propelConfig;
        $this->fileSystem = $fileSystem;
    }

    /**
     * @return string
     */
    public function getSchemaDirectory(): string
    {
        $schemaDirectory = $this->propelConfig->getSchemaDirectory();

        if (!$this->fileSystem->exists($schemaDirectory)) {
            $this->fileSystem->mkdir($schemaDirectory);
        }

        return $schemaDirectory;
    }
}
