<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCartShare\Plugin;

use Spryker\Client\PersistentCartShareExtension\Dependency\Plugin\CartShareOptionPluginInterface;

class PreviewCartShareOptionPlugin implements CartShareOptionPluginInterface
{
    protected const KEY_PREVIEW = 'PREVIEW';
    protected const GROUP_EXTERNAL = 'external';

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

    /**
     * @inheritDoc
     * - Returns external share option group.
     *
     * @api
     *
     * @return string
     */
    public function getShareOptionGroup(): string
    {
        return static::GROUP_EXTERNAL;
    }
}
