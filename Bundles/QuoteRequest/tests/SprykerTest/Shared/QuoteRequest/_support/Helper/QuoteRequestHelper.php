<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\QuoteRequest\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\QuoteRequestBuilder;
use Generated\Shared\DataBuilder\QuoteRequestVersionBuilder;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class QuoteRequestHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    public function haveQuoteRequest(array $seed = []): QuoteRequestTransfer
    {
        $quoteRequestTransfer = (new QuoteRequestBuilder($seed))->build();

        return $this->getLocator()->quoteRequest()->facade()->createQuoteRequest($quoteRequestTransfer)->getQuoteRequest();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param string $status
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    public function haveQuoteRequestInStatus(QuoteTransfer $quoteTransfer, CompanyUserTransfer $companyUserTransfer, string $status): QuoteRequestTransfer
    {
        $quoteRequestTransfer = $this->haveQuoteRequest([
            QuoteRequestTransfer::LATEST_VERSION => $this->haveQuoteRequestVersion(
                [QuoteRequestVersionTransfer::QUOTE => $quoteTransfer]
            ),
            QuoteRequestTransfer::COMPANY_USER => $companyUserTransfer,
        ]);

        $quoteRequestTransfer->setStatus($status);

        $quoteRequestResponseTransfer = $this->getLocator()->quoteRequest()->facade()->updateQuoteRequest($quoteRequestTransfer);

        return $quoteRequestResponseTransfer->getQuoteRequest();
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
