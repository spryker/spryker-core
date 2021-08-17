<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Authorization\Authorization;

use Spryker\Client\AuthorizationExtension\Dependency\Plugin\AuthorizationStrategyPluginInterface;

interface AuthorizationStrategyCollectionInterface
{
    /**
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool;

    /**
     * @param string $name
     *
     * @return \Spryker\Client\AuthorizationExtension\Dependency\Plugin\AuthorizationStrategyPluginInterface
     */
    public function get(string $name): AuthorizationStrategyPluginInterface;
}
