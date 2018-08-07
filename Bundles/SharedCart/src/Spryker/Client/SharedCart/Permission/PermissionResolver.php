<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SharedCart\Permission;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\PermissionAwareTrait;
use Spryker\Client\SharedCart\Dependency\Client\SharedCartToCustomerClientInterface;
use Spryker\Client\SharedCart\Plugin\WriteSharedCartPermissionPlugin;
use Spryker\Client\SharedCart\SharedCartConfig;

class PermissionResolver implements PermissionResolverInterface
{
    use PermissionAwareTrait;

    /**
     * @var \Spryker\Client\SharedCart\Dependency\Client\SharedCartToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @var \Spryker\Client\SharedCart\SharedCartConfig
     */
    protected $config;

    /**
     * @param \Spryker\Client\SharedCart\Dependency\Client\SharedCartToCustomerClientInterface $customerClient
     * @param \Spryker\Client\SharedCart\SharedCartConfig $config
     */
    public function __construct(
        SharedCartToCustomerClientInterface $customerClient,
        SharedCartConfig $config
    ) {
        $this->customerClient = $customerClient;
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    public function getQuoteAccessLevel(QuoteTransfer $quoteTransfer): string
    {
        $customerTransfer = $this->customerClient->getCustomer();

        if ($customerTransfer) {
            if ($customerTransfer->getCustomerReference() === $quoteTransfer->getCustomerReference()) {
                return $this->config->getOwnerPermission();
            }

            $writeAllowed = $this->can(
                WriteSharedCartPermissionPlugin::KEY,
                $quoteTransfer->getIdQuote()
            );
            if ($writeAllowed) {
                return $this->config->getFullPermission();
            }
        }

        return $this->config->getReadPermission();
    }
}
