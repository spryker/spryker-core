<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Storage;

interface StorageClientWithCacheInterface extends StorageClientInterface
{

    /**
     * @api
     *
     * @return \Spryker\Client\Storage\StorageClientInterface $service
     *
     * @todo move these to client interface directly
     */
    public function getService();

    /**
     * @api
     *
     * @return array
     */
    public function getCachedKeys();

    /**
     * @api
     *
     * @param string $key
     *
     * @return void
     */
    public function unsetCachedKey($key);

    /**
     * @api
     *
     * @return void
     */
    public function unsetLastCachedKey();

}
