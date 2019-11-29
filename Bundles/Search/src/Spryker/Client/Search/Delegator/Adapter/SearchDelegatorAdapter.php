<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Delegator\Adapter;

use Generated\Shared\Transfer\SearchContextTransfer;
use Generated\Shared\Transfer\SearchDocumentTransfer;
use Spryker\Client\Search\Delegator\SearchDelegatorInterface;
use Spryker\Client\Search\Exception\InvalidDataSetException;

/**
 * @deprecated Will be removed without replacement.
 */
class SearchDelegatorAdapter implements SearchDelegatorAdapterInterface
{
    protected const DEFAULT_SOURCE_IDENTIFIER = 'page';

    /**
     * @var \Spryker\Client\Search\Delegator\SearchDelegatorInterface
     */
    protected $searchDelegator;

    /**
     * @param \Spryker\Client\Search\Delegator\SearchDelegatorInterface $searchDelegator
     */
    public function __construct(SearchDelegatorInterface $searchDelegator)
    {
        $this->searchDelegator = $searchDelegator;
    }

    /**
     * @param string $key
     * @param string|null $typeName
     * @param string|null $indexName
     *
     * @return mixed
     */
    public function read(string $key, ?string $typeName = null, ?string $indexName = null)
    {
        $searchDocumentTransfer = $this->createSearchDocumentTransfer($key, $typeName);

        return $this->searchDelegator->readDocument($searchDocumentTransfer);
    }

    /**
     * @param array $documentDataSet
     * @param string|null $typeName
     * @param string|null $indexName
     *
     * @return bool
     */
    public function write(array $documentDataSet, ?string $typeName = null, ?string $indexName = null): bool
    {
        [$documentId, $documentDataSet] = $this->getDocumentAttributesFromDocumentDataSet($documentDataSet);
        $searchDocumentTransfer = $this->createSearchDocumentTransfer($documentId, $typeName, $documentDataSet);

        return $this->searchDelegator->writeDocument($searchDocumentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer[] $searchDocumentTransfers
     *
     * @return bool
     */
    public function writeBulk(array $searchDocumentTransfers): bool
    {
        $searchDocumentTransfers = $this->addSearchContextToSearchDocumentTransfers($searchDocumentTransfers);

        return $this->searchDelegator->writeDocuments($searchDocumentTransfers);
    }

    /**
     * @param array $documentDataSet
     * @param string|null $typeName
     * @param string|null $indexName
     *
     * @return bool
     */
    public function delete(array $documentDataSet, ?string $typeName = null, ?string $indexName = null): bool
    {
        [$documentId] = $this->getDocumentAttributesFromDocumentDataSet($documentDataSet);
        $searchDocumentTransfer = $this->createSearchDocumentTransfer($documentId, $typeName);

        return $this->searchDelegator->deleteDocument($searchDocumentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer[] $searchDocumentTransfers
     *
     * @return bool
     */
    public function deleteBulk(array $searchDocumentTransfers): bool
    {
        $searchDocumentTransfers = $this->addSearchContextToSearchDocumentTransfers($searchDocumentTransfers);

        return $this->searchDelegator->deleteDocuments($searchDocumentTransfers);
    }

    /**
     * @param string $documentId
     * @param string|null $typeName
     * @param array|null $documentData
     *
     * @return \Generated\Shared\Transfer\SearchDocumentTransfer
     */
    protected function createSearchDocumentTransfer(string $documentId, ?string $typeName = null, ?array $documentData = null): SearchDocumentTransfer
    {
        $typeName = $typeName ?? static::DEFAULT_SOURCE_IDENTIFIER;
        $searchContextTransfer = $this->createSearchContextTransferFromMappingType($typeName);

        return (new SearchDocumentTransfer())->setId($documentId)
            ->setData($documentData)
            ->setSearchContext($searchContextTransfer);
    }

    /**
     * @param array $documentDataSet
     *
     * @throws \Spryker\Client\Search\Exception\InvalidDataSetException
     *
     * @return array
     */
    protected function getDocumentAttributesFromDocumentDataSet(array $documentDataSet): array
    {
        $documentId = array_key_first($documentDataSet);

        if (!$documentId) {
            throw new InvalidDataSetException('Document id is not found in data set.');
        }

        $documentData = $documentDataSet[$documentId];

        return [$documentId, $documentData];
    }

    /**
     * @param string $sourceIdentifier
     *
     * @return \Generated\Shared\Transfer\SearchContextTransfer
     */
    protected function createSearchContextTransferFromMappingType(string $sourceIdentifier): SearchContextTransfer
    {
        return (new SearchContextTransfer())->setSourceIdentifier($sourceIdentifier);
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer[] $searchDocumentTransfers
     *
     * @return \Generated\Shared\Transfer\SearchDocumentTransfer[]
     */
    protected function addSearchContextToSearchDocumentTransfers(array $searchDocumentTransfers): array
    {
        return array_map(function (SearchDocumentTransfer $searchDocumentTransfer) {
            return $this->addSearchContextToSearchDocumentTransfer($searchDocumentTransfer);
        }, $searchDocumentTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return \Generated\Shared\Transfer\SearchDocumentTransfer
     */
    protected function addSearchContextToSearchDocumentTransfer(SearchDocumentTransfer $searchDocumentTransfer): SearchDocumentTransfer
    {
        $searchContextTransfer = $this->createSearchContextTransferFromMappingType($searchDocumentTransfer->getType());
        $searchDocumentTransfer->setSearchContext($searchContextTransfer);

        return $searchDocumentTransfer;
    }
}
