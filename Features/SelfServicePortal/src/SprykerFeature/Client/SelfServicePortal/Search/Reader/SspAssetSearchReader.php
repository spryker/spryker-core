<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerFeature\Client\SelfServicePortal\Search\Reader;

use Generated\Shared\Transfer\SspAssetSearchCollectionTransfer;
use Generated\Shared\Transfer\SspAssetSearchCriteriaTransfer;
use Spryker\Client\CompanyUser\CompanyUserClientInterface;
use Spryker\Client\Search\SearchClientInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\SearchStringSetterInterface;

class SspAssetSearchReader implements SspAssetSearchReaderInterface
{
    /**
     * @var string
     */
    protected const SEARCH_RESULT_KEY = 'ssp-asset-search';

    /**
     * @var string
     */
    protected const PARAMETER_SEARCH_STRING = 'q';

    /**
     * @var string
     */
    protected const PARAMETER_OFFSET = 'offset';

    /**
     * @var string
     */
    protected const PARAMETER_LIMIT = 'limit';

    /**
     * @var string
     */
    protected const PARAMETER_SORT = 'sort';

    /**
     * @param \Spryker\Client\Search\SearchClientInterface $searchClient
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $sspAssetSearchQueryPlugin
     * @param \Spryker\Client\CompanyUser\CompanyUserClientInterface $companyUserClient
     * @param array<\Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface> $sspAssetSearchQueryExpanderPlugins
     * @param array<\Spryker\Client\SearchExtension\Dependency\Plugin\ResultFormatterPluginInterface> $sspAssetSearchResultFormatterPlugins
     */
    public function __construct(
        protected SearchClientInterface $searchClient,
        protected QueryInterface $sspAssetSearchQueryPlugin,
        protected CompanyUserClientInterface $companyUserClient,
        protected array $sspAssetSearchQueryExpanderPlugins = [],
        protected array $sspAssetSearchResultFormatterPlugins = []
    ) {
    }

    public function getSspAssetSearchCollection(
        SspAssetSearchCriteriaTransfer $sspAssetSearchCriteriaTransfer
    ): SspAssetSearchCollectionTransfer {
        if (!$this->companyUserClient->findCompanyUser()) {
            return new SspAssetSearchCollectionTransfer();
        }

        $searchQuery = $this->sspAssetSearchQueryPlugin;

        $searchString = $sspAssetSearchCriteriaTransfer->getSearchString();
        $requestParameters = $this->prepareRequestParameters($sspAssetSearchCriteriaTransfer);

        if ($searchString && $searchQuery instanceof SearchStringSetterInterface) {
            $searchQuery->setSearchString($searchString);
        }

        $searchQuery = $this->searchClient->expandQuery(
            $searchQuery,
            $this->sspAssetSearchQueryExpanderPlugins,
            $requestParameters,
        );

        $result = $this->searchClient->search(
            $searchQuery,
            $this->sspAssetSearchResultFormatterPlugins,
            $requestParameters,
        );

        return $result[static::SEARCH_RESULT_KEY] ?? new SspAssetSearchCollectionTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetSearchCriteriaTransfer $criteriaTransfer
     *
     * @return array<string, mixed>
     */
    protected function prepareRequestParameters(SspAssetSearchCriteriaTransfer $criteriaTransfer): array
    {
        $parameters = [];

        if ($criteriaTransfer->getSearchString()) {
            $parameters[static::PARAMETER_SEARCH_STRING] = $criteriaTransfer->getSearchString();
        }

        if ($criteriaTransfer->getPagination()) {
            $pagination = $criteriaTransfer->getPagination();
            if ($pagination->getOffset()) {
                $parameters[static::PARAMETER_OFFSET] = $pagination->getOffset();
            }
            if ($pagination->getLimit()) {
                $parameters[static::PARAMETER_LIMIT] = $pagination->getLimit();
            }
        }

        if ($criteriaTransfer->getSort()) {
            $parameters[static::PARAMETER_SORT] = $criteriaTransfer->getSort();
        }

        return $parameters;
    }
}
