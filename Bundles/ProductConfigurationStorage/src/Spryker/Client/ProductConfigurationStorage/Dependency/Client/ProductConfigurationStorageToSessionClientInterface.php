<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Dependency\Client;

interface ProductConfigurationStorageToSessionClientInterface
{
    /**
     * Checks if an attribute is defined.
     *
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name);

    /**
     * Returns an attribute.
     *
     * @param string $name
     * @param mixed $default The default value if not found
     *
     * @return mixed
     */
    public function get(string $name, $default = null);

    /**
     * Sets an attribute.
     *
     * @param string $name
     * @param mixed $value
     */
    public function set(string $name, $value);
}
