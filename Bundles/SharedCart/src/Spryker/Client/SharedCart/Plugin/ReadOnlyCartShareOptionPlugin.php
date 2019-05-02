<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SharedCart\Plugin;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\PersistentCartShareExtension\Dependency\Plugin\CartShareOptionPluginInterface;
use Spryker\Shared\SharedCart\SharedCartConfig as SharedSharedCartConfig;

/**
 * @method \Spryker\Client\SharedCart\SharedCartFactory getFactory()
 */
class ReadOnlyCartShareOptionPlugin extends AbstractPlugin implements CartShareOptionPluginInterface
{
    protected const KEY_READ_ONLY = 'READ_ONLY';

    /**
     * {@inheritdoc}
     * - Returns read-only share option key.
     *
     * @api
     *
     * @return string
     */
    public function getKey(): string
    {
        return static::KEY_READ_ONLY;
    }

    /**
     * {@inheritDoc}
     * - Returns internal share option group.
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
