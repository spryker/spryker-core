<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequestsRestApi\Business\Reader;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\QuoteRequestsRestApi\Dependency\Facade\QuoteRequestsRestApiToCartsRestApiFacadeInterface;

class QuoteReader implements QuoteReaderInterface
{
    /**
     * @param \Spryker\Zed\QuoteRequestsRestApi\Dependency\Facade\QuoteRequestsRestApiToCartsRestApiFacadeInterface $cartsRestApiFacade
     */
    public function __construct(
        protected QuoteRequestsRestApiToCartsRestApiFacadeInterface $cartsRestApiFacade
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param string $uuid
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function findQuoteByUuidForCustomer(
        CustomerTransfer $customerTransfer,
        string $uuid
    ): QuoteResponseTransfer {
        $quoteTransfer = (new QuoteTransfer())
            ->setCustomerReference($customerTransfer->getCustomerReference())
            ->setCustomer($customerTransfer)
            ->setUuid($uuid);

        return $this->cartsRestApiFacade->findQuoteByUuid($quoteTransfer);
    }
}
