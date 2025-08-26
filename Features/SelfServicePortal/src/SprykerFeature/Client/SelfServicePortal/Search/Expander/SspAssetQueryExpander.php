<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SelfServicePortal\Search\Expander;

use Elastica\Query\BoolQuery;
use Elastica\Query\MatchAll;
use Elastica\Query\Terms;
use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\SspAssetStorageTransfer;
use Generated\Shared\Transfer\SspModelStorageTransfer;
use Spryker\Client\CompanyUser\CompanyUserClientInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use SprykerFeature\Client\SelfServicePortal\Storage\Reader\SspAssetStorageReaderInterface;
use SprykerFeature\Client\SelfServicePortal\Storage\Reader\SspModelStorageReaderInterface;

class SspAssetQueryExpander implements SspAssetQueryExpanderInterface
{
    /**
     * @var string
     */
    protected const PARAMETER_SSP_ASSET_REFERENCE = 'ssp-asset-reference';

    public function __construct(
        protected SspAssetStorageReaderInterface $sspAssetStorageReader,
        protected SspModelStorageReaderInterface $sspModelStorageReader,
        protected CompanyUserClientInterface $companyUserClient
    ) {
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array<string, mixed> $requestParameters
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(
        QueryInterface $searchQuery,
        array $requestParameters = []
    ): QueryInterface {
        $sspAssetReference = $requestParameters[static::PARAMETER_SSP_ASSET_REFERENCE] ?? null;
        if (!$sspAssetReference || !is_string($sspAssetReference)) {
            return $searchQuery;
        }

        $companyUserTransfer = $this->companyUserClient->findCompanyUser();
        if (!$companyUserTransfer) {
            return $searchQuery;
        }

        $sspAssetStorageTransfer = $this->sspAssetStorageReader->findSspAssetStorageByReference($sspAssetReference, $companyUserTransfer);
        if (!$sspAssetStorageTransfer) {
            $this->applyNoResultsFilter($searchQuery);

            return $searchQuery;
        }

        $modelIds = $this->extractModelIds($sspAssetStorageTransfer);
        if ($modelIds === []) {
            $this->applyNoResultsFilter($searchQuery);

            return $searchQuery;
        }

        $sspModelStorageTransfers = $this->sspModelStorageReader->getSspModelStoragesByIds($modelIds);
        if ($sspModelStorageTransfers === []) {
            $this->applyNoResultsFilter($searchQuery);

            return $searchQuery;
        }

        $whitelistIds = $this->extractWhitelistIds($sspModelStorageTransfers);
        if ($whitelistIds === []) {
            $this->applyNoResultsFilter($searchQuery);

            return $searchQuery;
        }

        $this->applyWhitelistFilter($searchQuery, $whitelistIds);

        return $searchQuery;
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param list<int> $whitelistIds
     *
     * @return void
     */
    protected function applyWhitelistFilter(QueryInterface $searchQuery, array $whitelistIds): void
    {
        $query = $searchQuery->getSearchQuery();
        $boolQuery = $this->getBoolQuery($query);

        $whitelistQuery = new Terms(PageIndexMap::PRODUCT_LISTS_WHITELISTS, $whitelistIds);
        $boolQuery->addFilter($whitelistQuery);
    }

    protected function applyNoResultsFilter(QueryInterface $searchQuery): void
    {
        $query = $searchQuery->getSearchQuery();
        $boolQuery = $this->getBoolQuery($query);

        $noResultsQuery = new MatchAll();
        $boolQuery->addMustNot($noResultsQuery);
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetStorageTransfer $sspAssetStorageTransfer
     *
     * @return list<int>
     */
    protected function extractModelIds(SspAssetStorageTransfer $sspAssetStorageTransfer): array
    {
        $modelIds = [];

        foreach ($sspAssetStorageTransfer->getSspModels() as $sspModelTransfer) {
            $idSspModel = $sspModelTransfer->getIdSspModel();
            if ($idSspModel) {
                $modelIds[] = $idSspModel;
            }
        }

        return $modelIds;
    }

    /**
     * @param list<\Generated\Shared\Transfer\SspModelStorageTransfer> $sspModelStorageTransfers
     *
     * @return list<int>
     */
    protected function extractWhitelistIds(array $sspModelStorageTransfers): array
    {
        $whitelistIds = [];

        foreach ($sspModelStorageTransfers as $sspModelStorageTransfer) {
            $whitelistIds[] = $this->extractWhitelistIdsFromModel($sspModelStorageTransfer);
        }

        $whitelistIds = array_merge(...$whitelistIds);

        return array_values(array_unique($whitelistIds));
    }

    /**
     * @param \Generated\Shared\Transfer\SspModelStorageTransfer $sspModelStorageTransfer
     *
     * @return list<int>
     */
    protected function extractWhitelistIdsFromModel(SspModelStorageTransfer $sspModelStorageTransfer): array
    {
        $whitelistIds = [];

        foreach ($sspModelStorageTransfer->getWhitelists() as $productListStorageTransfer) {
            $whitelistId = $productListStorageTransfer->getIdProductList();
            if ($whitelistId) {
                $whitelistIds[] = $whitelistId;
            }
        }

        return $whitelistIds;
    }

    /**
     * @param mixed $query
     *
     * @return \Elastica\Query\BoolQuery
     */
    protected function getBoolQuery($query): BoolQuery
    {
        $boolQuery = $query->getQuery();
        if (!$boolQuery instanceof BoolQuery) {
            $boolQuery = new BoolQuery();
            $query->setQuery($boolQuery);
        }

        return $boolQuery;
    }
}
