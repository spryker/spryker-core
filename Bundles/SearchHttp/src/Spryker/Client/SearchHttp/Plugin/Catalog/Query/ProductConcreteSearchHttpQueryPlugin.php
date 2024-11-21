<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Plugin\Catalog\Query;

use Generated\Shared\Transfer\SearchContextTransfer;
use Generated\Shared\Transfer\SearchHttpSearchContextTransfer;
use Generated\Shared\Transfer\SearchQueryTransfer;
use Spryker\Client\CatalogExtension\Dependency\Plugin\QueryApplicabilityCheckerInterface;
use Spryker\Client\CatalogExtension\Dependency\Plugin\SearchTypeIdentifierInterface;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchContextAwareQueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchStringSetterInterface;
use Spryker\Shared\SearchHttp\SearchHttpConfig;

/**
 * @method \Spryker\Client\SearchHttp\SearchHttpFactory getFactory()
 */
class ProductConcreteSearchHttpQueryPlugin extends AbstractPlugin implements SearchContextAwareQueryInterface, SearchStringSetterInterface, QueryInterface, QueryApplicabilityCheckerInterface, SearchTypeIdentifierInterface
{
    /**
     * @var \Generated\Shared\Transfer\SearchQueryTransfer;
     */
    protected SearchQueryTransfer $searchQueryTransfer;

    /**
     * @var \Generated\Shared\Transfer\SearchContextTransfer
     */
    protected SearchContextTransfer $searchContextTransfer;

    public function __construct()
    {
        $this->searchContextTransfer = (new SearchContextTransfer())
            ->setSourceIdentifier(SearchHttpConfig::SOURCE_IDENTIFIER_PRODUCT)
            ->setSearchHttpContext(new SearchHttpSearchContextTransfer());
        $this->searchQueryTransfer = (new SearchQueryTransfer())
            ->setLocale($this->getFactory()->getLocaleClient()->getCurrentLocale())
            ->setUserToken($this->getFactory()->getCustomerClient()->getUserIdentifier());
    }

    /**
     * {@inheritDoc}
     * - Returns query object for catalog search.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\SearchQueryTransfer
     */
    public function getSearchQuery(): SearchQueryTransfer
    {
        return $this->searchQueryTransfer;
    }

    /**
     * {@inheritDoc}
     * - Defines a context for catalog search.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\SearchContextTransfer
     */
    public function getSearchContext(): SearchContextTransfer
    {
        return $this->searchContextTransfer ?? (new SearchContextTransfer())
            ->setSearchHttpContext(new SearchHttpSearchContextTransfer());
    }

    /**
     * {@inheritDoc}
     * - Sets a context for catalog search.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return void
     */
    public function setSearchContext(SearchContextTransfer $searchContextTransfer): void
    {
        $this->searchContextTransfer = $searchContextTransfer;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $searchString
     *
     * @return void
     */
    public function setSearchString($searchString): void
    {
        $this->searchQueryTransfer->setQueryString($searchString);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return bool
     */
    public function isApplicable(): bool
    {
        return $this->getFactory()->createQueryApplicabilityChecker()->isQueryApplicable();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getSearchType(): string
    {
        return SearchHttpConfig::TYPE_PRODUCT_CONCRETE_SEARCH_HTTP;
    }
}
