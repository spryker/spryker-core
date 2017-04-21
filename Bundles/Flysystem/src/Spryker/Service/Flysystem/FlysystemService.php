<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem;

use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\Flysystem\FlysystemServiceFactory getFactory()
 */
class FlysystemService extends AbstractService implements FlysystemServiceInterface
{

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $name
     *
     * @return \Spryker\Service\Flysystem\Model\FlysystemStorageInterface
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
     * @return \Spryker\Service\Flysystem\Model\FlysystemStorageInterface[]
     */
    public function getStorageCollection()
    {
        return $this->getFactory()
            ->createStorageProvider()
            ->getStorageCollection();
    }

}
