<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SharedCart\Plugin;

use Spryker\Client\PersistentCartShareExtension\Dependency\Plugin\CartShareOptionPluginInterface;

class PreviewCartShareOptionPlugin implements CartShareOptionPluginInterface
{
    protected const KEY_PREVIEW = 'PREVIEW';

    /**
     * {@inheritdoc}
     * - Returns preview share option key.
     *
     * @api
     *
     * @return string
     */
    public function getKey(): string
    {
        return static::KEY_PREVIEW;
    }
}
