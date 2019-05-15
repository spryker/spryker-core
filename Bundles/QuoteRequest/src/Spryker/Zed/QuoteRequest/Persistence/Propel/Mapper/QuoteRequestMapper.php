<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteRequestCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequest;
use Propel\Runtime\Collection\Collection;

class QuoteRequestMapper
{
    /**
     * @param \Propel\Runtime\Collection\Collection $quoteRequestEntities
     *
     * @return \Generated\Shared\Transfer\QuoteRequestCollectionTransfer
     */
    public function mapEntityCollectionToTransferCollection(
        Collection $quoteRequestEntities
    ): QuoteRequestCollectionTransfer {
        $quoteRequestCollectionTransfer = new QuoteRequestCollectionTransfer();

        foreach ($quoteRequestEntities as $quoteRequestEntity) {
            $quoteRequestTransfer = $this->mapQuoteRequestEntityToQuoteRequestTransfer(
                $quoteRequestEntity,
                new QuoteRequestTransfer()
            );
            $quoteRequestCollectionTransfer->addQuoteRequest($quoteRequestTransfer);
        }

        return $quoteRequestCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     * @param \Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequest $quoteRequestEntity
     *
     * @return \Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequest
     */
    public function mapQuoteRequestTransferToQuoteRequestEntity(
        QuoteRequestTransfer $quoteRequestTransfer,
        SpyQuoteRequest $quoteRequestEntity
    ): SpyQuoteRequest {
        $quoteRequestEntity->fromArray($quoteRequestTransfer->modifiedToArray());
        $quoteRequestEntity->setFkCompanyUser($quoteRequestTransfer->getCompanyUser()->getIdCompanyUser());

        return $quoteRequestEntity;
    }

    /**
     * @param \Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequest $quoteRequestEntity
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    public function mapQuoteRequestEntityToQuoteRequestTransfer(
        SpyQuoteRequest $quoteRequestEntity,
        QuoteRequestTransfer $quoteRequestTransfer
    ): QuoteRequestTransfer {
        $quoteRequestTransfer = $quoteRequestTransfer->fromArray($quoteRequestEntity->toArray(), true);

        $quoteRequestTransfer->setCompanyUser($this->getCompanyUserTransfer($quoteRequestEntity));

        return $quoteRequestTransfer;
    }

    /**
     * @param \Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequest $quoteRequestEntity
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected function getCompanyUserTransfer(SpyQuoteRequest $quoteRequestEntity): CompanyUserTransfer
    {
        $customerTransfer = (new CustomerTransfer())
            ->fromArray($quoteRequestEntity->getCompanyUser()->getCustomer()->toArray(), true);

        $companyTransfer = (new CompanyTransfer())
            ->fromArray($quoteRequestEntity->getCompanyUser()->getCompany()->toArray(), true);

        $companyUserTransfer = (new CompanyUserTransfer())
            ->fromArray($quoteRequestEntity->getCompanyUser()->toArray(), true);

        $companyUserTransfer
            ->setCustomer($customerTransfer)
            ->setCompany($companyTransfer);

        return $companyUserTransfer;
    }
}
