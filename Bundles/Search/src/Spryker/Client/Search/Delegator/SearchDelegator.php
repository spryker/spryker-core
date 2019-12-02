<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Delegator;

use Exception;
use Generated\Shared\Transfer\SearchContextTransfer;
use Generated\Shared\Transfer\SearchDocumentTransfer;
use Spryker\Client\Search\Exception\SearchDelegatorException;
use Spryker\Client\Search\SearchContext\SearchContextExpanderInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchAdapterPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchContextAwareQueryInterface;

class SearchDelegator implements SearchDelegatorInterface
{
    /**
     * @var \Spryker\Client\SearchExtension\Dependency\Plugin\SearchAdapterPluginInterface[]
     */
    protected $searchAdapterPlugins;

    /**
     * @var \Spryker\Client\Search\SearchContext\SearchContextExpanderInterface
     */
    protected $searchContextExpander;

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\SearchAdapterPluginInterface[] $searchAdapterPlugins
     * @param \Spryker\Client\Search\SearchContext\SearchContextExpanderInterface $searchContextExpander
     */
    public function __construct(array $searchAdapterPlugins, SearchContextExpanderInterface $searchContextExpander)
    {
        $this->searchAdapterPlugins = $this->getSearchAdapterPluginsIndexedByName($searchAdapterPlugins);
        $this->searchContextExpander = $searchContextExpander;
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $resultFormatters
     * @param array $requestParameters
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function search(QueryInterface $searchQuery, array $resultFormatters = [], array $requestParameters = [])
    {
        if (!$searchQuery instanceof SearchContextAwareQueryInterface) {
            throw new Exception(sprintf('Your query class "%s" must implement %s interface.', get_class($searchQuery), SearchContextAwareQueryInterface::class));
        }

        $searchQuery = $this->expandSearchContextTransferForQuery($searchQuery);

        return $this->getSearchAdapter($searchQuery->getSearchContext())
            ->search($searchQuery, $resultFormatters, $requestParameters);
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return mixed
     */
    public function readDocument(SearchDocumentTransfer $searchDocumentTransfer)
    {
        $searchDocumentTransfer = $this->expandSearchContextTransferForSearchDocumentTransfer($searchDocumentTransfer);

        return $this->getSearchAdapter($searchDocumentTransfer->getSearchContext())->readDocument($searchDocumentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return bool
     */
    public function writeDocument(SearchDocumentTransfer $searchDocumentTransfer): bool
    {
        $searchDocumentTransfer = $this->expandSearchContextTransferForSearchDocumentTransfer($searchDocumentTransfer);
        $plugin = $this->getSearchAdapter($searchDocumentTransfer->getSearchContext());

        return $plugin->writeDocument($searchDocumentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer[] $searchDocumentTransfers
     *
     * @return bool
     */
    public function writeDocuments(array $searchDocumentTransfers): bool
    {
        $overallResult = true;
        $searchDocumentTransfers = $this->expandSearchContextTransferForSearchDocumentTransfers($searchDocumentTransfers);
        $searchDocumentTransfersBySearchAdapterPluginName = $this->groupSearchDocumentTransfersBySearchAdapterPluginName($searchDocumentTransfers);

        foreach ($searchDocumentTransfersBySearchAdapterPluginName as $searchAdapterPluginName => $searchDocumentTransfers) {
            $singleOperationResult = $this->searchAdapterPlugins[$searchAdapterPluginName]->writeDocuments($searchDocumentTransfers);

            if (!$singleOperationResult) {
                $overallResult = false;
            }
        }

        return $overallResult;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return bool
     */
    public function deleteDocument(SearchDocumentTransfer $searchDocumentTransfer): bool
    {
        $searchDocumentTransfer = $this->expandSearchContextTransferForSearchDocumentTransfer($searchDocumentTransfer);
        $plugin = $this->getSearchAdapter($searchDocumentTransfer->getSearchContext());

        return $plugin->deleteDocument($searchDocumentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer[] $searchDocumentTransfers
     *
     * @return bool
     */
    public function deleteDocuments(array $searchDocumentTransfers): bool
    {
        $overallResult = true;
        $searchDocumentTransfers = $this->expandSearchContextTransferForSearchDocumentTransfers($searchDocumentTransfers);
        $searchDocumentTransfersBySearchAdapterPluginName = $this->groupSearchDocumentTransfersBySearchAdapterPluginName($searchDocumentTransfers);

        foreach ($searchDocumentTransfersBySearchAdapterPluginName as $searchAdapterPluginName => $searchDocumentTransfersPerAdapter) {
            $singleOperationResult = $this->searchAdapterPlugins[$searchAdapterPluginName]->deleteDocuments($searchDocumentTransfersPerAdapter);

            if (!$singleOperationResult) {
                $overallResult = false;
            }
        }

        return $overallResult;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer[] $searchDocumentTransfers
     *
     * @return \Generated\Shared\Transfer\SearchDocumentTransfer[][]
     */
    protected function groupSearchDocumentTransfersBySearchAdapterPluginName(array $searchDocumentTransfers): array
    {
        $searchContextTransfersPerAdapter = [];

        foreach ($searchDocumentTransfers as $searchDocumentTransfer) {
            $searchAdapterPlugin = $this->getSearchAdapter($searchDocumentTransfer->getSearchContext());
            $searchContextTransfersPerAdapter[$searchAdapterPlugin->getName()][] = $searchDocumentTransfer;
        }

        return $searchContextTransfersPerAdapter;
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\SearchAdapterPluginInterface[] $searchAdapterPlugins
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\SearchAdapterPluginInterface[]
     */
    protected function getSearchAdapterPluginsIndexedByName(array $searchAdapterPlugins): array
    {
        $searchAdapterPluginsIndexedByVendorName = [];

        foreach ($searchAdapterPlugins as $searchAdapterPlugin) {
            $searchAdapterPluginsIndexedByVendorName[$searchAdapterPlugin->getName()] = $searchAdapterPlugin;
        }

        return $searchAdapterPluginsIndexedByVendorName;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @throws \Spryker\Client\Search\Exception\SearchDelegatorException
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\SearchAdapterPluginInterface
     */
    protected function getSearchAdapter(SearchContextTransfer $searchContextTransfer): SearchAdapterPluginInterface
    {
        foreach ($this->searchAdapterPlugins as $searchAdapterPlugin) {
            if ($searchAdapterPlugin->isApplicable($searchContextTransfer)) {
                return $searchAdapterPlugin;
            }
        }

        throw new SearchDelegatorException(sprintf(
            'None of the applied "%s"s is applicable for the specified context.',
            SearchAdapterPluginInterface::class
        ));
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    protected function expandSearchContextTransferForQuery(QueryInterface $searchQuery): QueryInterface
    {
        $mappedSearchContextTransfer = $this->expandSearchContext($searchQuery->getSearchContext());
        $searchQuery->setSearchContext($mappedSearchContextTransfer);

        return $searchQuery;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer[] $searchDocumentTransfers
     *
     * @return \Generated\Shared\Transfer\SearchDocumentTransfer[]
     */
    protected function expandSearchContextTransferForSearchDocumentTransfers(array $searchDocumentTransfers): array
    {
        return array_map(function (SearchDocumentTransfer $searchDocumentTransfer) {
            return $this->expandSearchContextTransferForSearchDocumentTransfer($searchDocumentTransfer);
        }, $searchDocumentTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\SearchDocumentTransfer $searchDocumentTransfer
     *
     * @return \Generated\Shared\Transfer\SearchDocumentTransfer
     */
    protected function expandSearchContextTransferForSearchDocumentTransfer(SearchDocumentTransfer $searchDocumentTransfer): SearchDocumentTransfer
    {
        $mappedSearchContextTransfer = $this->expandSearchContext($searchDocumentTransfer->getSearchContext());
        $searchDocumentTransfer->setSearchContext($mappedSearchContextTransfer);

        return $searchDocumentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return \Generated\Shared\Transfer\SearchContextTransfer
     */
    protected function expandSearchContext(SearchContextTransfer $searchContextTransfer): SearchContextTransfer
    {
        return $this->searchContextExpander->expandSearchContext($searchContextTransfer);
    }
}
