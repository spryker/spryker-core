<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApi\Plugin;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestAttributesTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestTransfer;
use Spryker\Glue\CustomersRestApiExtension\Dependency\Plugin\CustomerPostRegisterPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Shared\Quote\QuoteConfig;

/**
 * @method \Spryker\Glue\CartsRestApi\CartsRestApiFactory getFactory()
 */
class UpdateQuoteCustomerReferencePlugin extends AbstractPlugin implements CustomerPostRegisterPluginInterface
{
    /**
     * {@inheritdoc}
     *  - Updates guest customer stored cart with registered customer reference.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function postRegister(CustomerTransfer $customerTransfer): CustomerTransfer
    {
        if ($this->getFactory()->getQuoteClient()->getStorageStrategy() !== QuoteConfig::STORAGE_STRATEGY_DATABASE) {
            return $customerTransfer;
        }

        $quoteUpdateRequestAttributesTransfer = (new QuoteUpdateRequestAttributesTransfer())
            ->setCustomerReference($customerTransfer->getCustomerReference());
        $quoteUpdateRequestTransfer = (new QuoteUpdateRequestTransfer())
            ->setIdQuote($this->getFactory()->getQuoteClient()->getQuote()->getIdQuote())
            ->setCustomer($customerTransfer)
            ->setQuoteUpdateRequestAttributes($quoteUpdateRequestAttributesTransfer);
        $this->getFactory()->getPersistentCartClient()->updateQuote($quoteUpdateRequestTransfer);

        return $customerTransfer;
    }
}
