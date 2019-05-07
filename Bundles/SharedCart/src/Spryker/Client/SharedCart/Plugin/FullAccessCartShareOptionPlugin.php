<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SharedCart\Plugin;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\PersistentCartShareExtension\Dependency\Plugin\CartShareOptionPluginInterface;
use Spryker\Shared\SharedCart\SharedCartConfig as SharedSharedCartConfig;

class FullAccessCartShareOptionPlugin extends AbstractPlugin implements CartShareOptionPluginInterface
{
    protected const KEY_FULL_ACCESS = 'FULL_ACCESS';

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getKey(): string
    {
        return static::KEY_FULL_ACCESS;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getShareOptionGroup(): string
    {
        return SharedSharedCartConfig::SHARE_OPTION_GROUP_INTERNAL;
    }
}
