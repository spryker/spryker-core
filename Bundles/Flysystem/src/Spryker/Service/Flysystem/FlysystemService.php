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
     * @return \League\Flysystem\Filesystem
     */
    public function getFilesystemByName($name)
    {
        return $this->getFactory()
            ->createFilesystemProvider()
            ->getFilesystemByName($name);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \League\Flysystem\Filesystem[]
     */
    public function getFilesystemCollection()
    {
        return $this->getFactory()
            ->createFilesystemProvider()
            ->getFilesystemCollection();
    }

}
