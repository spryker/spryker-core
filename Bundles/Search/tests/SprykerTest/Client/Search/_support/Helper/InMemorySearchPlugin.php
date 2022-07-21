<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Search\Helper;

use Generated\Shared\Transfer\SearchContextTransfer;
use Generated\Shared\Transfer\SearchDocumentTransfer;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use SprykerTest\Shared\Testify\Helper\MessageFormatter;

class InMemorySearchPlugin implements InMemorySearchPluginInterface
{
    use MessageFormatter;

    /**
     * @var array<array<\Generated\Shared\Transfer\SearchDocumentTransfer>>
     */
    protected $data = [];

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $resultFormatters
     * @param array<string, mixed> $requestParameters
     *
     * @return mixed
     */
    public function search(QueryInterface $searchQuery, array $resultFormatters = [], array $requestParameters = [])
    {
        codecept_debug($this->format(sprintf('%s is not implemented yet.', __METHOD__)));

        return [];
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return \Generated\Shared\Transfer\SearchDocumentTransfer
     */
    public function readDocument(SearchDocumentTransfer $searchDocumentTransfer): SearchDocumentTransfer
    {
        $searchDocumentId = $searchDocumentTransfer->getIdOrFail();
        $sourceIdentifier = $searchDocumentTransfer->getSearchContextOrFail()->getSourceIdentifierOrFail();

        if (!isset($this->data[$sourceIdentifier])) {
            codecept_debug($this->format(sprintf(
                'Search document <fg=yellow>%s</> not found in the source "%s".',
                $searchDocumentId,
                $sourceIdentifier,
            )));

            return new SearchDocumentTransfer();
        }

        if (isset($this->data[$sourceIdentifier][$searchDocumentId])) {
            return $this->data[$sourceIdentifier][$searchDocumentId];
        }

        return new SearchDocumentTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return bool
     */
    public function deleteDocument(SearchDocumentTransfer $searchDocumentTransfer): bool
    {
        $searchDocumentId = $searchDocumentTransfer->getIdOrFail();
        $sourceIdentifier = $searchDocumentTransfer->getSearchContextOrFail()->getSourceIdentifierOrFail();

        if (!isset($this->data[$sourceIdentifier])) {
            codecept_debug($this->format(sprintf(
                'Search document <fg=yellow>%s</> not found in the source "%s".',
                $searchDocumentId,
                $sourceIdentifier,
            )));

            return false;
        }

        unset($this->data[$sourceIdentifier][$searchDocumentId]);

        return true;
    }

    /**
     * @param array $searchDocumentTransfers
     *
     * @return bool
     */
    public function deleteDocuments(array $searchDocumentTransfers): bool
    {
        foreach ($searchDocumentTransfers as $searchDocumentTransfer) {
            $this->deleteDocument($searchDocumentTransfer);
        }

        return true;
    }

    /**
     * We always assume that this Adapter can be used.
     *
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return bool
     */
    public function isApplicable(SearchContextTransfer $searchContextTransfer): bool
    {
        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return bool
     */
    public function writeDocument(SearchDocumentTransfer $searchDocumentTransfer): bool
    {
        $searchDocumentId = $searchDocumentTransfer->getIdOrFail();
        $sourceIdentifier = $searchDocumentTransfer->getSearchContextOrFail()->getSourceIdentifierOrFail();

        if (!isset($this->data[$sourceIdentifier])) {
            $this->data[$sourceIdentifier] = [];
        }

        if (isset($this->data[$sourceIdentifier][$searchDocumentId])) {
            codecept_debug($this->format(sprintf(
                'Search document <fg=yellow>%s</> already exists in the source <fg=yellow>%s</>.',
                $searchDocumentId,
                $sourceIdentifier,
            )));
        }

        $this->data[$sourceIdentifier][$searchDocumentId] = $searchDocumentTransfer;

        codecept_debug($this->format(sprintf(
            'Search document <fg=yellow>%s</> added to the source <fg=yellow>%s</>.',
            $searchDocumentId,
            $sourceIdentifier,
        )));

        return true;
    }

    /**
     * @param array<\Generated\Shared\Transfer\SearchDocumentTransfer> $searchDocumentTransfers
     *
     * @return bool
     */
    public function writeDocuments(array $searchDocumentTransfers): bool
    {
        foreach ($searchDocumentTransfers as $searchDocumentTransfer) {
            $this->writeDocument($searchDocumentTransfer);
        }

        return true;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'in memory search adapter';
    }

    /**
     * @param string $source
     *
     * @return array
     */
    public function getAllKeys(string $source): array
    {
        if (!isset($this->data[$source])) {
            return [];
        }

        return array_keys($this->data[$source]);
    }

    /**
     * @return void
     */
    public function deleteAll(): void
    {
        $this->data = [];
    }
}
