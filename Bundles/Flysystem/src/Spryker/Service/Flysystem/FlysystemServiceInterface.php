<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Flysystem;

/**
 * @method \Spryker\Service\Flysystem\FlysystemServiceFactory getFactory()
 */
interface FlysystemServiceInterface
{

    /**
     * @api
     *
     * @param string $name
     *
     * @return \League\Flysystem\Filesystem
     */
    public function getStorageByName($name);

    /**
     * @api
     *
     * @return \League\Flysystem\Filesystem[]
     */
    public function getStorageCollection();

}
