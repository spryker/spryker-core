<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCartShare\CartShareOption;

use Generated\Shared\Transfer\CustomerTransfer;

class CartShareOptionReader implements CartShareOptionReaderInterface
{
    /**
     * @var array<\Spryker\Client\PersistentCartShareExtension\Dependency\Plugin\CartShareOptionPluginInterface>
     */
    protected $cartShareOptionPlugins;

    /**
     * @param array<\Spryker\Client\PersistentCartShareExtension\Dependency\Plugin\CartShareOptionPluginInterface> $cartShareOptionPlugins
     */
    public function __construct(array $cartShareOptionPlugins)
    {
        $this->cartShareOptionPlugins = $cartShareOptionPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return array<array<string>>
     */
    public function getCartShareOptions(?CustomerTransfer $customerTransfer): array
    {
        $cartShareOptions = [];
        foreach ($this->cartShareOptionPlugins as $cartShareOptionPlugin) {
            if (!$cartShareOptionPlugin->isApplicable($customerTransfer)) {
                continue;
            }
            $cartShareOptions[$cartShareOptionPlugin->getShareOptionGroup()][] = $cartShareOptionPlugin->getShareOptionKey();
        }

        return $cartShareOptions;
    }
}
