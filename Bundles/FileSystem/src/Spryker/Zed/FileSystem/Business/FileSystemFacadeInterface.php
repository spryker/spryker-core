<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Business\FileSystem;

/**
 * @method \Spryker\Business\FileSystem\FileSystemServiceFactory getFactory()
 */
interface FileSystemFacadeInterface
{

    /**
     * @api
     *
     * @param string $name
     *
     * @return \Spryker\Business\FileSystem\Model\FileSystemStorageInterface
     */
    public function getStorageByName($name);

    /**
     * @api
     *
     * @return \Spryker\Business\FileSystem\Model\FileSystemStorageInterface[]
     */
    public function getStorageCollection();

}
