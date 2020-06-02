<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search;

use Generated\Shared\Transfer\SearchDocumentTransfer;
use Spryker\Client\Kernel\AbstractClient;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

/**
 * @method \Spryker\Client\Search\SearchFactory getFactory()
 */
class SearchClient extends AbstractClient implements SearchClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @throws \Exception
     *
     * @return void
     */
    public function checkConnection()
    {
        $this->getFactory()
            ->getElasticsearchClient()
            ->getStatus()
            ->getData();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface[] $searchQueryExpanders
     * @param array $requestParameters
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $searchQueryExpanders, array $requestParameters = [])
    {
        foreach ($searchQueryExpanders as $searchQueryExpander) {
            $searchQuery = $searchQueryExpander->expandQuery($searchQuery, $requestParameters);
        }

        return $searchQuery;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface[] $resultFormatters
     * @param array $requestParameters
     *
     * @return array|\Elastica\ResultSet|mixed (@deprecated Only mixed will be supported with the next major)
     */
    public function search(QueryInterface $searchQuery, array $resultFormatters = [], array $requestParameters = [])
    {
        return $this
            ->getFactory()
            ->createElasticsearchSearchHandler()
            ->search($searchQuery, $resultFormatters, $requestParameters);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Will be removed without replacement. This functionality is obsolete and should not be used.
     *
     * @return \Spryker\Client\Search\Dependency\Plugin\SearchConfigInterface
     */
    public function getSearchConfig()
    {
        return $this->getFactory()->getSearchConfig();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $searchString
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return array|\Elastica\ResultSet|mixed (@deprecated Only mixed will be supported with the next major)
     */
    public function searchKeys($searchString, $limit = null, $offset = null)
    {
        $query = $this
            ->getFactory()
            ->createSearchKeysQuery($searchString, $limit, $offset);

        return $this->search($query);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param string $searchString
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return array|\Elastica\ResultSet|mixed (@deprecated Only mixed will be supported with the next major)
     */
    public function searchQueryString($searchString, $limit = null, $offset = null)
    {
        $query = $this
            ->getFactory()
            ->createSearchStringQuery($searchString, $limit, $offset);

        return $this->search($query);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Client\Search\SearchClient::readDocument()} instead.
     *
     * @param string $key
     * @param string|null $typeName
     * @param string|null $indexName
     *
     * @return mixed
     */
    public function read($key, $typeName = null, $indexName = null)
    {
        return $this
            ->getFactory()
            ->createReader()
            ->read($key, $typeName, $indexName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return mixed
     */
    public function readDocument(SearchDocumentTransfer $searchDocumentTransfer)
    {
        return $this->getFactory()->createSearchDelegator()->readDocument($searchDocumentTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Client\Search\SearchClient::writeDocument()} instead.
     *
     * @param array $dataSet
     * @param string|null $typeName
     * @param string|null $indexName
     *
     * @return bool
     */
    public function write(array $dataSet, $typeName = null, $indexName = null)
    {
        return $this
            ->getFactory()
            ->createWriter()
            ->write($dataSet, $typeName, $indexName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return bool
     */
    public function writeDocument(SearchDocumentTransfer $searchDocumentTransfer): bool
    {
        return $this->getFactory()->createSearchDelegator()->writeDocument($searchDocumentTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Client\Search\SearchClient::writeDocuments()} instead.
     *
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer[] $searchDocumentTransfers
     *
     * @return bool
     */
    public function writeBulk(array $searchDocumentTransfers): bool
    {
        return $this
            ->getFactory()
            ->createWriter()
            ->writeBulk($searchDocumentTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer[] $searchDocumentTransfers
     *
     * @return bool
     */
    public function writeDocuments(array $searchDocumentTransfers): bool
    {
        return $this->getFactory()->createSearchDelegator()->writeDocuments($searchDocumentTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Client\Search\SearchClient::deleteDocument()} instead.
     *
     * @param array $dataSet
     * @param string|null $typeName
     * @param string|null $indexName
     *
     * @return bool
     */
    public function delete(array $dataSet, $typeName = null, $indexName = null)
    {
        return $this
            ->getFactory()
            ->createWriter()
            ->delete($dataSet, $typeName, $indexName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return bool
     */
    public function deleteDocument(SearchDocumentTransfer $searchDocumentTransfer): bool
    {
        return $this
            ->getFactory()
            ->createSearchDelegator()
            ->deleteDocument($searchDocumentTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Client\Search\SearchClient::deleteDocuments()} instead.
     *
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer[] $searchDocumentTransfers
     *
     * @return bool
     */
    public function deleteBulk(array $searchDocumentTransfers): bool
    {
        return $this
            ->getFactory()
            ->createWriter()
            ->deleteBulk($searchDocumentTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer[] $searchDocumentTransfers
     *
     * @return bool
     */
    public function deleteDocuments(array $searchDocumentTransfers): bool
    {
        return $this
            ->getFactory()
            ->createSearchDelegator()
            ->deleteDocuments($searchDocumentTransfers);
    }
}
