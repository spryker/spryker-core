<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCartShare\CartShareOption;

class CartShareOptionReader implements CartShareOptionReaderInterface
{
    /**
     * @var \Spryker\Client\PersistentCartShareExtension\Dependency\Plugin\CartShareOptionPluginInterface[]
     */
    protected $cartShareOptionPlugins;

    /**
     * @param array $cartShareOptionPlugins
     */
    public function __construct(array $cartShareOptionPlugins)
    {
        $this->cartShareOptionPlugins = $cartShareOptionPlugins;
    }

    /**
     * @return string[][]
     */
    public function getCartShareOptions(): array
    {
        $cartShareOptions = [];
        foreach ($this->cartShareOptionPlugins as $cartShareOptionPlugin) {
            $cartShareOptions[$cartShareOptionPlugin->getGroup()][] = $cartShareOptionPlugin->getKey();
        }

        return $cartShareOptions;
    }
}
