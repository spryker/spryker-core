<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Search\Helper;

use Codeception\TestInterface;
use Generated\Shared\Transfer\SearchContextTransfer;
use Generated\Shared\Transfer\SearchDocumentTransfer;
use Spryker\Client\Search\SearchClientInterface;
use Spryker\Client\Search\SearchDependencyProvider;
use Spryker\Client\Search\SearchFactory;
use SprykerTest\Client\Testify\Helper\ClientHelperTrait;
use SprykerTest\Client\Testify\Helper\DependencyProviderHelperTrait;
use SprykerTest\Shared\Testify\Helper\AbstractHelper;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;
use SprykerTest\Shared\Testify\Helper\StaticVariablesHelper;

class SearchHelper extends AbstractHelper
{
    use ClientHelperTrait;
    use LocatorHelperTrait;
    use DependencyProviderHelperTrait;
    use StaticVariablesHelper;

    /**
     * @var \SprykerTest\Client\Search\Helper\InMemorySearchPluginInterface|null
     */
    protected $inMemorySearchPlugin;

    /**
     * @var \Spryker\Client\Search\SearchClientInterface|null
     */
    protected $searchClient;

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        $this->cleanupInMemorySearch();
        $this->initializeClient();

        $this->cleanupStaticCache(SearchFactory::class, 'searchConfigInstance', null);
        $this->cleanupStaticCache(SearchFactory::class, 'searchClient', null);
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test): void
    {
        $this->resetStaticCaches();
    }

    /**
     * @param string $key
     * @param string $source
     *
     * @return void
     */
    public function assertSearchHasKey(string $key, string $source): void
    {
        $allSourceKeys = $this->getInMemorySearchPlugin()->getAllKeys($source);

        $this->assertTrue(in_array($key, $allSourceKeys), $this->format(sprintf(
            '<fg=yellow>%s</> not found in the search source <fg=yellow>%s</> but was expected to be there. Keys in the search: %s',
            $key,
            $source,
            implode(', ', $allSourceKeys),
        )));
    }

    /**
     * @param string $key
     * @param string $source
     *
     * @return void
     */
    public function assertSearchNotHasKey(string $key, string $source): void
    {
        $allSourceKeys = $this->getInMemorySearchPlugin()->getAllKeys($source);

        $this->assertFalse(in_array($key, $allSourceKeys), $this->format(sprintf(
            '<fg=yellow>%s</> found in the search source <fg=yellow>%s</> but was not expected to be there.',
            $key,
            $source,
        )));
    }

    /**
     * This will clean-up the in-memory search.
     *
     * @return void
     */
    public function cleanupInMemorySearch(): void
    {
        $inMemorySearch = $this->getInMemorySearchPlugin();
        $inMemorySearch->deleteAll();
    }

    /**
     * @return \Spryker\Client\Search\SearchClientInterface
     */
    public function getSearchClient(): SearchClientInterface
    {
        return $this->searchClient;
    }

    /**
     * @return \SprykerTest\Client\Search\Helper\InMemorySearchPluginInterface
     */
    protected function getInMemorySearchPlugin(): InMemorySearchPluginInterface
    {
        if ($this->inMemorySearchPlugin === null) {
            $this->inMemorySearchPlugin = new InMemorySearchPlugin();
        }

        return $this->inMemorySearchPlugin;
    }

    /**
     * Creates a StorageClient with an in-memory (Search) PluginInterface and ensures that the locator also returns this mock
     * when used with `$locator->search()->client()`.
     *
     * @return void
     */
    protected function initializeClient(): void
    {
        $this->getDependencyProviderHelper()->setDependency(
            SearchDependencyProvider::PLUGINS_CLIENT_ADAPTER,
            [$this->getInMemorySearchPlugin()],
        );

        // Resolves the SearchClientInterface with all mocks from above.
        /** @var \Spryker\Client\Search\SearchClientInterface $searchClient */
        $searchClient = $this->getClientHelper()->getClient('Search');
        $this->searchClient = $searchClient;

        // Ensure `$locator->search()->client()` returns always the SearchClientInterface with all mocks from above.
        $this->getLocatorHelper()->addToLocatorCache('search-client', $this->searchClient);
    }

    /**
     * @param string $id
     * @param array $data
     * @param string $sourceIdentifier
     *
     * @return void
     */
    public function mockSearchData(string $id, array $data, string $sourceIdentifier): void
    {
        $searchDocumentTransfer = new SearchDocumentTransfer();
        $searchDocumentTransfer->setId($id);
        $searchDocumentTransfer->setData($data);

        $searchContextTransfer = new SearchContextTransfer();
        $searchContextTransfer->setSourceIdentifier($sourceIdentifier);

        $searchDocumentTransfer->setSearchContext($searchContextTransfer);

        $this->getInMemorySearchPlugin()->writeDocument($searchDocumentTransfer);
    }
}
