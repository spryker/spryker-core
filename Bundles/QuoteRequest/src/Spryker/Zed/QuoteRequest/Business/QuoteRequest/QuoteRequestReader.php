<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Business\QuoteRequest;

use Generated\Shared\Transfer\QuoteRequestCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCompanyUserInterface;
use Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface;

class QuoteRequestReader implements QuoteRequestReaderInterface
{
    /**
     * @var \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCompanyUserInterface
     */
    protected $companyUserFacade;

    /**
     * @param \Spryker\Zed\QuoteRequest\Persistence\QuoteRequestRepositoryInterface $repository
     * @param \Spryker\Zed\QuoteRequest\Dependency\Facade\QuoteRequestToCompanyUserInterface $companyUserFacade
     */
    public function __construct(
        QuoteRequestRepositoryInterface $repository,
        QuoteRequestToCompanyUserInterface $companyUserFacade
    ) {
        $this->repository = $repository;
        $this->companyUserFacade = $companyUserFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestFilterTransfer $quoteRequestFilterTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestCollectionTransfer
     */
    public function findQuoteRequestCollectionByFilter(QuoteRequestFilterTransfer $quoteRequestFilterTransfer): QuoteRequestCollectionTransfer
    {
        $idCompanyUser = $quoteRequestFilterTransfer->requireCompanyUser()
            ->getCompanyUser()
            ->requireIdCompanyUser()
            ->getIdCompanyUser();

        $companyUserTransfer = $this->companyUserFacade->getCompanyUserById($idCompanyUser);
        $quoteRequestCollectionTransfer = $this->repository->findQuoteRequestCollectionByFilter($quoteRequestFilterTransfer);

        foreach ($quoteRequestCollectionTransfer->getQuoteRequests() as $quoteRequestTransfer) {
            $quoteRequestTransfer->setCompanyUser($companyUserTransfer);
        }

        return $quoteRequestCollectionTransfer;
    }
}
