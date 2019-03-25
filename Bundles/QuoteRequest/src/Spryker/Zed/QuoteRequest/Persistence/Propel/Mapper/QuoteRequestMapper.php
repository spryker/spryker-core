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
use Spryker\Zed\QuoteRequest\QuoteRequestConfig;

class QuoteRequestMapper
{
    /**
     * @var \Spryker\Zed\QuoteRequest\QuoteRequestConfig
     */
    protected $quoteRequestConfig;

    /**
     * @var \Spryker\Zed\QuoteRequest\Dependency\Service\QuoteRequestToUtilEncodingServiceInterface
     */
    protected $encodingService;

    /**
     * @param \Spryker\Zed\QuoteRequest\QuoteRequestConfig $quoteRequestConfig
     * @param \Spryker\Zed\QuoteRequest\Dependency\Service\QuoteRequestToUtilEncodingServiceInterface $encodingService
     */
    public function __construct(
        QuoteRequestConfig $quoteRequestConfig,
        QuoteRequestToUtilEncodingServiceInterface $encodingService
    ) {
        $this->encodingService = $encodingService;
        $this->quoteRequestConfig = $quoteRequestConfig;
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

        $quoteRequestTransfer->setCompanyUser($this->getCompanyUserTransfer($quoteRequestEntity))
            ->setLatestVersion($this->getLatestQuoteRequestVersion($quoteRequestEntity))
            ->setLatestVisibleVersion($this->findLatestVisibleQuoteRequestVersion($quoteRequestEntity))
            ->setVersionReferences($this->getVersionReferences($quoteRequestEntity));

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
        $quoteRequestEntity->fromArray($quoteRequestTransfer->modifiedToArray())
            ->setFkCompanyUser($quoteRequestTransfer->getCompanyUser()->getIdCompanyUser());

        return $quoteRequestEntity;
    }

    /**
     * @param \Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequest $quoteRequestEntity
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer
     */
    protected function findLatestVisibleQuoteRequestVersion(SpyQuoteRequest $quoteRequestEntity): QuoteRequestVersionTransfer
    {
        if (!$quoteRequestEntity->getIsLatestVersionHidden()) {
            return $this->getLatestQuoteRequestVersion($quoteRequestEntity);
        }

        /** @var \Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestVersion[] $quoteRequestVersionEntities */
        $quoteRequestVersionEntities = $quoteRequestEntity->getSpyQuoteRequestVersions()->getArrayCopy();

        end($quoteRequestVersionEntities);
        $quoteRequestVersionEntity = prev($quoteRequestVersionEntities);

        if (!$quoteRequestVersionEntity) {
            return null;
        }

        return $this->mapQuoteRequestVersionTransfer($quoteRequestVersionEntity);
    }

    /**
     * @param \Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequest $quoteRequestEntity
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer
     */
    protected function getLatestQuoteRequestVersion(SpyQuoteRequest $quoteRequestEntity): QuoteRequestVersionTransfer
    {
        /** @var \Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestVersion $quoteRequestVersionEntity */
        $quoteRequestVersionEntity = $quoteRequestEntity->getSpyQuoteRequestVersions()->getLast();

        return $this->mapQuoteRequestVersionTransfer($quoteRequestVersionEntity);
    }

    /**
     * @param \Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestVersion $quoteRequestVersionEntity
     *
     * @return \Generated\Shared\Transfer\QuoteRequestVersionTransfer
     */
    protected function mapQuoteRequestVersionTransfer(SpyQuoteRequestVersion $quoteRequestVersionEntity): QuoteRequestVersionTransfer
    {
        $latestQuoteRequestVersionTransfer = (new QuoteRequestVersionTransfer())
            ->fromArray($quoteRequestVersionEntity->toArray(), true)
            ->setQuote($this->getQuote($quoteRequestVersionEntity->getQuote()))
            ->setMetadata($this->decodeMetadata($quoteRequestVersionEntity->getMetadata()));

        return $latestQuoteRequestVersionTransfer;
    }

    /**
     * @param \Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequest $quoteRequestEntity
     *
     * @return string[]
     */
    protected function getVersionReferences(SpyQuoteRequest $quoteRequestEntity): array
    {
        $versionReferences = [];

        foreach ($quoteRequestEntity->getSpyQuoteRequestVersions() as $spyQuoteRequestVersion) {
            $versionReferences[] = $spyQuoteRequestVersion->getVersionReference();
        }

        return $versionReferences;
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
     * @param string|null $quote
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer|null
     */
    protected function getQuote(?string $quote): ?QuoteTransfer
    {
        if (!$quote) {
            return null;
        }

        return (new QuoteTransfer())
            ->fromArray($this->decodeQuoteData($quote), true);
    }

    /**
     * @param string $data
     *
     * @return array
     */
    protected function decodeQuoteData(string $data): array
    {
        return $this->encodingService->decodeJson($data, true);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function encodeQuoteData(QuoteTransfer $quoteTransfer): string
    {
        $quoteData = $this->filterDisallowedQuoteData($quoteTransfer);

        return $this->encodingService->encodeJson($quoteData, JSON_OBJECT_AS_ARRAY);
    }

    /**
     * @param \Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestVersion $quoteRequestVersionEntity
     *
     * @return array
     */
    protected function decodeMetadata(SpyQuoteRequestVersion $quoteRequestVersionEntity): array
    {
        return $this->encodingService->decodeJson($quoteRequestVersionEntity->getMetadata(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestVersionTransfer $quoteRequestVersionTransfer
     *
     * @return string
     */
    protected function encodeMetadata(QuoteRequestVersionTransfer $quoteRequestVersionTransfer): string
    {
        return $this->encodingService->encodeJson($quoteRequestVersionTransfer->getMetadata(), JSON_OBJECT_AS_ARRAY);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function filterDisallowedQuoteData(QuoteTransfer $quoteTransfer): array
    {
        $data = [];
        $quoteData = $quoteTransfer->modifiedToArray(true, true);

        foreach ($this->quoteRequestConfig->getQuoteFieldsAllowedForSaving() as $dataKey) {
            if (isset($quoteData[$dataKey])) {
                $data[$dataKey] = $quoteData[$dataKey];
            }
        }

        return $data;
    }
}
