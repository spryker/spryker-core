<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Storage;

use Spryker\Client\Storage\ServiceProvider\StorageClientServiceProvider;

trait StorageClientFactoryTrait
{

    /**
     * @return \Spryker\Client\Storage\StorageClientInterface
     */
    public function getStorageClient()
    {
        return $this->getProvidedDependency(StorageClientServiceProvider::CLIENT_STORAGE);
    }

    /**
     * @param string $key
     *
     * @throws \Spryker\Client\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return mixed
     */
    abstract public function getProvidedDependency($key);

}
