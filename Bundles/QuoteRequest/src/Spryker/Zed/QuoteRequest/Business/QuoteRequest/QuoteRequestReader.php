<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Business\QuoteRequest;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteRequestCollectionTransfer;
use Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface;

class QuoteRequestReader implements QuoteRequestReaderInterface
{
    /**
     * @var \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface
     */
    protected $repository;

    /**
     * @param \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface $repository
     */
    public function __construct(QuoteRequestRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestCollectionTransfer
     */
    public function getCustomerQuoteRequestCollection(CustomerTransfer $customerTransfer): QuoteRequestCollectionTransfer
    {
        $idCompanyUser = $customerTransfer
            ->requireCompanyUserTransfer()
            ->getCompanyUserTransfer()
            ->getIdCompanyUser();

        $quoteRequestCollection = $this->repository->getQuoteRequestCollectionByIdCompanyUser($idCompanyUser);

        return $quoteRequestCollection;
    }
}
