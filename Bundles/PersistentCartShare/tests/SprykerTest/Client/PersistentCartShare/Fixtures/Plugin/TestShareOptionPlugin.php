<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\PersistentCartShare\Fixtures\Plugin;

use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\PersistentCartShareExtension\Dependency\Plugin\CartShareOptionPluginInterface;

class TestShareOptionPlugin extends AbstractPlugin implements CartShareOptionPluginInterface
{
    protected const KEY_TEST = 'key-test';
    protected const GROUP_TEST = 'group-test';

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getKey(): string
    {
        return static::KEY_TEST;
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
        return static::GROUP_TEST;
    }
}
