<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem;

use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\FileSystem\FileSystemServiceFactory getFactory()
 */
class FileSystemService extends AbstractService implements FileSystemServiceInterface
{

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $name
     *
     * @return \Spryker\Service\FileSystem\Model\FileSystemStorageInterface
     */
    public function getStorageByName($name)
    {
        return $this->getFactory()
            ->createStorageProvider()
            ->getStorageByName($name);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Spryker\Service\FileSystem\Model\FileSystemStorageInterface[]
     */
    public function getStorageCollection()
    {
        return $this->getFactory()
            ->createStorageProvider()
            ->getStorageCollection();
    }

}
