<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\FileSystem\Model\Provider;

interface FileSystemStorageProviderInterface
{

    /**
     * @param string $name
     *
     * @throws \Spryker\Service\FileSystem\Exception\FileSystemStorageNotFoundException
     *
     * @return \Spryker\Service\FileSystem\Model\FileSystemStorageInterface
     */
    public function getStorageByName($name);

    /**
     * @return \Spryker\Service\FileSystem\Model\FileSystemStorageInterface[]
     */
    public function getStorageCollection();

}
