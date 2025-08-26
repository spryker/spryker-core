<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerFeature\Client\SelfServicePortal\Plugin\Elasticsearch\Query;

use Elastica\Query;
use Generated\Shared\Transfer\SearchContextTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchContextAwareQueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchStringGetterInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchStringSetterInterface;

/**
 * @method \SprykerFeature\Client\SelfServicePortal\SelfServicePortalFactory getFactory()
 * @method \SprykerFeature\Client\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class SspAssetSearchQueryPlugin extends AbstractPlugin implements QueryInterface, SearchContextAwareQueryInterface, SearchStringSetterInterface, SearchStringGetterInterface
{
    /**
     * @uses \SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig::SSP_ASSET_RESOURCE_NAME
     *
     * @var string
     */
    protected const SOURCE_IDENTIFIER = 'ssp_asset';

    /**
     * @var \Elastica\Query
     */
    protected Query $query;

    /**
     * @var string|null
     */
    protected ?string $searchString = null;

    /**
     * @var \Generated\Shared\Transfer\SearchContextTransfer|null
     */
    protected ?SearchContextTransfer $searchContextTransfer = null;

    public function __construct()
    {
        $this->query = $this->createQuery();
    }

    /**
     * {@inheritDoc}
     * - Returns query object for SSP Asset search.
     *
     * @api
     *
     * @return \Elastica\Query
     */
    public function getSearchQuery(): Query
    {
        return $this->query;
    }

    /**
     * {@inheritDoc}
     * - Defines context for SSP Asset search.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\SearchContextTransfer
     */
    public function getSearchContext(): SearchContextTransfer
    {
        if (!$this->searchContextTransfer) {
            $this->searchContextTransfer = (new SearchContextTransfer())->setSourceIdentifier(static::SOURCE_IDENTIFIER);
        }

        return $this->searchContextTransfer;
    }

    /**
     * {@inheritDoc}
     * - Sets context for SSP Asset search.
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
        $this->searchString = $searchString;
        $this->query = $this->createQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string|null
     */
    public function getSearchString(): ?string
    {
        return $this->searchString;
    }

    protected function createQuery(): Query
    {
        return $this->getFactory()
            ->createSspAssetSearchQuery()
            ->createQuery($this->searchString);
    }
}
