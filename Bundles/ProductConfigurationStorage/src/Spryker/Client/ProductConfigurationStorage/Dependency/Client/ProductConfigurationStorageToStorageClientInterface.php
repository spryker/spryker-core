<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Dependency;

interface ProductConfigurationStorageToStorageClientInterface
{
    /**
     * Specification:
     *  - Get data from storage by string key.
     *
     * @api
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key);
}
