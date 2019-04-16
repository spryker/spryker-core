<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCartShare\Plugin;

use Spryker\Client\PersistentCartShareExtension\Dependency\Plugin\CartShareOptionPluginInterface;

class FullAccessCartShareOptionPlugin implements CartShareOptionPluginInterface
{
    protected const KEY_FULL_ACCESS = 'FULL_ACCESS';

    /**
     * {@inheritdoc}
     * - Returns full access share option key.
     *
     * @api
     *
     * @return string
     */
    public function getKey(): string
    {
        return static::KEY_FULL_ACCESS;
    }
}
