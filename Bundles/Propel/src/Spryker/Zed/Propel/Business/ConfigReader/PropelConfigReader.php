<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\ConfigReader;

use Spryker\Zed\Propel\Dependency\External\PropelToFileSystemAdapterInterface;
use Spryker\Zed\Propel\PropelConfig;

class PropelConfigReader implements PropelConfigReaderInterface
{
    /**
     * @var \Spryker\Zed\Propel\PropelConfig
     */
    protected $propelConfig;

    /**
     * @var \Spryker\Zed\Propel\Dependency\External\PropelToFileSystemAdapterInterface
     */
    protected $fileSystem;

    /**
     * @param \Spryker\Zed\Propel\PropelConfig $propelConfig
     * @param \Spryker\Zed\Propel\Dependency\External\PropelToFileSystemAdapterInterface $fileSystem
     */
    public function __construct(
        PropelConfig $propelConfig,
        PropelToFileSystemAdapterInterface $fileSystem
    ) {
        $this->propelConfig = $propelConfig;
        $this->fileSystem = $fileSystem;
    }

    /**
     * @return string
     */
    public function getSchemaDirectory(): string
    {
        $shemaDirectory = $this->propelConfig->getSchemaDirectory();

        if (!$this->fileSystem->exists($shemaDirectory)) {
            $this->fileSystem->mkdir($shemaDirectory);
        }

        return $shemaDirectory;
    }
}
