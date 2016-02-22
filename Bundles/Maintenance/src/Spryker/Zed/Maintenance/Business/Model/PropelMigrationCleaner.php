<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Maintenance\Business\Model;

use Symfony\Component\Filesystem\Filesystem;

class PropelMigrationCleaner implements PropelMigrationCleanerInterface
{

    /**
     * @var \Spryker\Zed\Maintenance\Business\Model\PropelBaseFolderFinderInterface
     */
    protected $finder;

    /**
     * @param \Spryker\Zed\Maintenance\Business\Model\PropelBaseFolderFinderInterface $finder
     */
    public function __construct(PropelBaseFolderFinderInterface $finder)
    {
        $this->finder = $finder;
    }

    /**
     * @inheritDoc
     */
    public function clean()
    {
        $result = false;

        $baseFolders = $this->finder->getBaseFolders();

        if (!empty($baseFolders)) {
            $result = true;

            $fileSystem = new Filesystem();
            foreach ($baseFolders as $folder) {
                $fileSystem->remove($folder);
            }
        }

        return $result;
    }

}
