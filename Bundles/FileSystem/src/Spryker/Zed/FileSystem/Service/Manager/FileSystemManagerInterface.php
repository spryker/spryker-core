<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileSystem\Service\Manager;

interface FileSystemManagerInterface
{

    /**
     * @param string $name
     *
     * @return \Spryker\Zed\FileSystem\Service\Storage\FileSystemStorageInterface
     */
    public function getStorageByName($name);

    /**
     * @return \Spryker\Zed\FileSystem\Service\Storage\FileSystemStorageInterface[]
     */
    public function getStorageCollection();

}
