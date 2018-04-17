<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SharedCart\Plugin;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\PersistentCartExtension\Dependency\Plugin\QuoteUpdatePluginInterface;

/**
 * @method \Spryker\Client\SharedCart\SharedCartFactory getFactory()
 */
class PermissionUpdateQuoteUpdatePlugin extends AbstractPlugin implements QuoteUpdatePluginInterface
{
    /**
     * Specification:
     * - Update customers permission collection.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function processResponse(QuoteResponseTransfer $quoteResponseTransfer): QuoteResponseTransfer
    {
        if (!$quoteResponseTransfer->getCustomerPermissions()) {
            return $quoteResponseTransfer;
        }
        $permissionCollectionTransfer = $quoteResponseTransfer->requireCustomerPermissions()->getCustomerPermissions();
        $customerClient = $this->getFactory()->getCustomerClient();
        $customerTransfer = $customerClient->getCustomer();
        $customerTransfer->setPermissions($permissionCollectionTransfer);
        $customerClient->setCustomer($customerTransfer);

        return $quoteResponseTransfer;
    }
}
