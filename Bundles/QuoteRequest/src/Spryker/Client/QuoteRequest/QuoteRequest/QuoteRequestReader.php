<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteRequest\QuoteRequest;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Spryker\Client\QuoteRequest\Zed\QuoteRequestStubInterface;

class QuoteRequestReader implements QuoteRequestReaderInterface
{
    /**
     * @var \Spryker\Client\QuoteRequest\Zed\QuoteRequestStubInterface
     */
    protected $quoteRequestStub;

    /**
     * @param \Spryker\Client\QuoteRequest\Zed\QuoteRequestStubInterface $quoteRequestStub
     */
    public function __construct(QuoteRequestStubInterface $quoteRequestStub)
    {
        $this->quoteRequestStub = $quoteRequestStub;
    }

    /**
     * @param string $quoteRequestReference
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer|null
     */
    public function findCompanyUserQuoteRequestByReference(string $quoteRequestReference, int $idCompanyUser): ?QuoteRequestTransfer
    {
        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference($quoteRequestReference)
            ->setCompanyUser((new CompanyUserTransfer())->setIdCompanyUser($idCompanyUser));

        $quoteRequestTransfers = $this->quoteRequestStub
            ->getQuoteRequestCollectionByFilter($quoteRequestFilterTransfer)
            ->getQuoteRequests()
            ->getArrayCopy();

        return array_shift($quoteRequestTransfers);
    }
}
