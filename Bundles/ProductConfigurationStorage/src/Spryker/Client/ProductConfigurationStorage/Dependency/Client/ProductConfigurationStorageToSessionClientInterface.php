<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Dependency\Client;

interface ProductConfigurationStorageToSessionClientInterface
{
    /**
     * @param string $name
     * @param $value
     *
     * @return mixed
     */
    public function set(string $name, $value);

    /**
     * @param string $name
     * @param null $default
     *
     * @return mixed
     */
    public function get(string $name, $default = null);
}
