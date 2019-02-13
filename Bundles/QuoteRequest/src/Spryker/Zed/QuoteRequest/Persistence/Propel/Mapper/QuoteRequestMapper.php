<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequest\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteRequestCollectionTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteRequestVersionTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequest;
use Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestVersion;
use Propel\Runtime\Collection\Collection;
use Spryker\Zed\QuoteRequest\Dependency\Service\QuoteRequestToUtilEncodingServiceInterface;

class QuoteRequestMapper
{
    /**
     * @var \Spryker\Zed\QuoteRequest\Dependency\Service\QuoteRequestToUtilEncodingServiceInterface
     */
    protected $encodingService;

    /**
     * @param \Spryker\Zed\QuoteRequest\Dependency\Service\QuoteRequestToUtilEncodingServiceInterface $encodingService
     */
    public function __construct(QuoteRequestToUtilEncodingServiceInterface $encodingService)
    {
        $this->encodingService = $encodingService;
    }

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
        $quoteRequestTransfer->setLatestVersion($this->findLatestQuoteRequestVersionTransfer($quoteRequestEntity));
        $quoteRequestTransfer->setMetadata($this->decodeMetadata($quoteRequestEntity));

        return $quoteRequestTransfer;
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
        $data = $quoteRequestTransfer->modifiedToArray();
        unset($data['metadata']);

        $quoteRequestEntity->fromArray($data);

        $quoteRequestEntity->setFkCompanyUser($quoteRequestTransfer->getCompanyUser()->getIdCompanyUser());
        $quoteRequestEntity->setMetadata($this->encodeMetadata($quoteRequestTransfer));

        return $quoteRequestEntity;
    }

    /**
     * @param \Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequest $quoteRequestEntity
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer|null
     */
    protected function findLatestQuoteRequestVersionTransfer(SpyQuoteRequest $quoteRequestEntity): ?QuoteRequestVersionTransfer
    {
        $quoteRequestVersionEntity = $quoteRequestEntity->getSpyQuoteRequestVersions()->getLast();

        if (!$quoteRequestVersionEntity) {
            return null;
        }

        $latestQuoteRequestVersionTransfer = (new QuoteRequestVersionTransfer())
            ->fromArray($quoteRequestVersionEntity->toArray(), true);

        $latestQuoteRequestVersionTransfer->setQuote(
            (new QuoteTransfer())->fromArray($this->decodeQuoteData($quoteRequestVersionEntity), true)
        );

        return $latestQuoteRequestVersionTransfer;
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

        $companyBusinessUnitTransfer = (new CompanyBusinessUnitTransfer())
            ->fromArray($quoteRequestEntity->getCompanyUser()->getCompanyBusinessUnit()->toArray(), true);

        return (new CompanyUserTransfer())
            ->fromArray($quoteRequestEntity->getCompanyUser()->toArray(), true)
            ->setCustomer($customerTransfer)
            ->setCompany($companyTransfer)
            ->setCompanyBusinessUnit($companyBusinessUnitTransfer);
    }

    /**
     * @param \Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequest $quoteRequestEntity
     *
     * @return array
     */
    protected function decodeMetadata(SpyQuoteRequest $quoteRequestEntity): array
    {
        return $this->encodingService->decodeJson($quoteRequestEntity->getMetadata(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return string
     */
    protected function encodeMetadata(QuoteRequestTransfer $quoteRequestTransfer): string
    {
        return $this->encodingService->encodeJson($quoteRequestTransfer->getMetadata(), JSON_OBJECT_AS_ARRAY);
    }

    /**
     * @param \Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestVersion $quoteRequestVersion
     *
     * @return array
     */
    protected function decodeQuoteData(SpyQuoteRequestVersion $quoteRequestVersion): array
    {
        return $this->encodingService->decodeJson($quoteRequestVersion->getQuote(), true);
    }
}
