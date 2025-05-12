<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\QuoteRequest\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\QuoteRequestBuilder;
use Generated\Shared\DataBuilder\QuoteRequestVersionBuilder;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class QuoteRequestHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    public function haveQuoteRequest(array $seed = []): QuoteRequestTransfer
    {
        $quoteRequestTransfer = (new QuoteRequestBuilder($seed))->build();
        $quoteRequestResponseTransfer = $this->getLocator()->quoteRequest()->facade()->createQuoteRequest($quoteRequestTransfer)->getQuoteRequest();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($quoteRequestResponseTransfer): void {
            if (!$quoteRequestResponseTransfer->getCompanyUser() || !$quoteRequestResponseTransfer->getCompanyUser()->getIdCompanyUser()) {
                return;
            }
            $this->debug(sprintf('Deleting Quote request for company user ID: %s', $quoteRequestResponseTransfer->getCompanyUser()->getIdCompanyUser()));
            $this->getLocator()->quoteRequest()->facade()->deleteQuoteRequestsByIdCompanyUser($quoteRequestResponseTransfer->getCompanyUser()->getIdCompanyUser());
        });

        return $quoteRequestTransfer;
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer
     */
    public function haveQuoteRequestVersion(array $seed = []): QuoteRequestVersionTransfer
    {
        $quoteRequestVersionTransfer = (new QuoteRequestVersionBuilder($seed))->build();

        return $quoteRequestVersionTransfer;
    }
}
