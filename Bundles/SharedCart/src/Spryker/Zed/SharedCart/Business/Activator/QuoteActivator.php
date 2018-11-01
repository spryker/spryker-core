<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SharedCart\Business\Activator;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\SharedCart\Persistence\SharedCartEntityManagerInterface;

class QuoteActivator implements QuoteActivatorInterface
{
    /**
     * @var \Spryker\Zed\SharedCart\Persistence\SharedCartEntityManagerInterface
     */
    protected $sharedCartEntityManager;

    /**
     * @param \Spryker\Zed\SharedCart\Persistence\SharedCartEntityManagerInterface $sharedCartEntityManager
     */
    public function __construct(SharedCartEntityManagerInterface $sharedCartEntityManager)
    {
        $this->sharedCartEntityManager = $sharedCartEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function setDefaultQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $this->sharedCartEntityManager->setQuoteDefault(
            $quoteTransfer->getCustomer()->getCompanyUserTransfer()->getIdCompanyUser(),
            $quoteTransfer->getIdQuote()
        );
        if (strcmp($quoteTransfer->getCustomer()->getCustomerReference(), $quoteTransfer->getCustomerReference()) === 0) {
            return $quoteTransfer;
        }
        $quoteData = $quoteTransfer->modifiedToArray(true, true);
        unset($quoteData[QuoteTransfer::IS_DEFAULT]);
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->fromArray($quoteData, true);

        return $quoteTransfer;
    }
}
