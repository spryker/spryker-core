<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequestAgentsRestApi\Business\Reader;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\QuoteRequestAgentsRestApi\Dependency\Facade\QuoteRequestAgentsRestApiToCartsRestApiFacadeInterface;

class QuoteReader implements QuoteReaderInterface
{
    /**
     * @var \Spryker\Zed\QuoteRequestAgentsRestApi\Dependency\Facade\QuoteRequestAgentsRestApiToCartsRestApiFacadeInterface
     */
    protected $cartsRestApiFacade;

    /**
     * @param \Spryker\Zed\QuoteRequestAgentsRestApi\Dependency\Facade\QuoteRequestAgentsRestApiToCartsRestApiFacadeInterface $cartsRestApiFacade
     */
    public function __construct(QuoteRequestAgentsRestApiToCartsRestApiFacadeInterface $cartsRestApiFacade)
    {
        $this->cartsRestApiFacade = $cartsRestApiFacade;
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
