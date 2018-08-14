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
use Spryker\Shared\SharedCart\SharedCartConfig;

class PermissionResolver implements PermissionResolverInterface
{
    use PermissionAwareTrait;

    /**
     * @var \Spryker\Client\SharedCart\Dependency\Client\SharedCartToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \Spryker\Client\SharedCart\Dependency\Client\SharedCartToCustomerClientInterface $customerClient
     */
    public function __construct(
        SharedCartToCustomerClientInterface $customerClient
    ) {
        $this->customerClient = $customerClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string|null key af access group or null if permissions can not be applied.
     */
    public function getQuoteAccessLevel(QuoteTransfer $quoteTransfer): ?string
    {
        $quoteTransfer
            ->requireIdQuote()
            ->requireCustomerReference();

        $customerTransfer = $this->customerClient->getCustomer();
        if (!$customerTransfer || !$customerTransfer->getCustomerReference()) {
            return null;
        }

        if ($customerTransfer->getCustomerReference() === $quoteTransfer->getCustomerReference()) {
            return SharedCartConfig::PERMISSION_GROUP_OWNER_ACCESS;
        }

        $writeAllowed = $this->can(
            WriteSharedCartPermissionPlugin::KEY,
            $quoteTransfer->getIdQuote()
        );
        if ($writeAllowed) {
            return SharedCartConfig::PERMISSION_GROUP_FULL_ACCESS;
        }

        $readAllowed = $this->can(
            WriteSharedCartPermissionPlugin::KEY,
            $quoteTransfer->getIdQuote()
        );
        if ($readAllowed) {
            return SharedCartConfig::PERMISSION_GROUP_READ_ONLY;
        }

        return null;
    }
}
