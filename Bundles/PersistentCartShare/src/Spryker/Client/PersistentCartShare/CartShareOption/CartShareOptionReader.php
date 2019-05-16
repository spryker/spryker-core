<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCartShare\CartShareOption;

use Spryker\Client\PersistentCartShare\Dependency\Client\PersistentCartShareToCustomerClientInterface;

class CartShareOptionReader implements CartShareOptionReaderInterface
{
    /**
     * @var \Spryker\Client\PersistentCartShareExtension\Dependency\Plugin\CartShareOptionPluginInterface[]
     */
    protected $cartShareOptionPlugins;

    /**
     * @var \Spryker\Client\PersistentCartShare\Dependency\Client\PersistentCartShareToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \Spryker\Client\PersistentCartShareExtension\Dependency\Plugin\CartShareOptionPluginInterface[] $cartShareOptionPlugins
     * @param \Spryker\Client\PersistentCartShare\Dependency\Client\PersistentCartShareToCustomerClientInterface $customerClient
     */
    public function __construct(array $cartShareOptionPlugins, PersistentCartShareToCustomerClientInterface $customerClient)
    {
        $this->cartShareOptionPlugins = $cartShareOptionPlugins;
        $this->customerClient = $customerClient;
    }

    /**
     * @return string[][]
     */
    public function getCartShareOptions(): array
    {
        $customerTransfer = $this->customerClient->getCustomer();
        $cartShareOptions = [];
        foreach ($this->cartShareOptionPlugins as $cartShareOptionPlugin) {
            if (!$cartShareOptionPlugin->isAllowedForCustomer($customerTransfer)) {
                continue;
            }
            $cartShareOptions[$cartShareOptionPlugin->getShareOptionGroup()][] = $cartShareOptionPlugin->getKey();
        }

        return $cartShareOptions;
    }
}
