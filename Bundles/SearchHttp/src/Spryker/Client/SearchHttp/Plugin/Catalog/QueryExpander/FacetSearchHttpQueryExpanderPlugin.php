<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\Plugin\Catalog\QueryExpander;

use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Generated\Shared\Transfer\FacetConfigTransfer;
use Generated\Shared\Transfer\SearchQueryRangeFacetFilterTransfer;
use Generated\Shared\Transfer\SearchQueryValueFacetFilterTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;
use Spryker\Shared\SearchHttp\SearchHttpConfig as SharedSearchHttpConfig;

/**
 * @method \Spryker\Client\SearchHttp\SearchHttpFactory getFactory()
 */
class FacetSearchHttpQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    /**
     * @var string
     */
    protected const KEY_MIN = 'min';

    /**
     * @var string
     */
    protected const KEY_MAX = 'max';

    /**
     * @var array<string>
     */
    protected const NON_FILTERING_GLUE_REQUEST_PARAMS = [
        'include',
        'q',
        'sort',
        'page',
        'ipp',
        'priceMode',
        'currency',
    ];

    /**
     * @var array<string>
     */
    protected const ALLOWED_DEFAULT_FACET_CONFIGS = [
        'category',
        'price',
    ];

    /**
     * {@inheritDoc}
     * - Applies facet filters to query
     * - Facet filter values that equal null, empty string or false are dropped other values are kept including 0(zero)
     *
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array<string, mixed> $requestParameters
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = []): QueryInterface
    {
        return $this->getFacetFilters($searchQuery, $requestParameters);
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array<string, mixed> $requestParameters
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    protected function getFacetFilters(QueryInterface $searchQuery, array $requestParameters = []): QueryInterface
    {
        $activeFacetConfigTransfers = $this->getFactory()->getSearchConfig()->getFacetConfig()->getActive($requestParameters);

        $activeFacetConfigTransfers = array_filter($activeFacetConfigTransfers, [$this, 'isAllowedActiveFacetConfig']);

        foreach ($requestParameters as $requestParameterName => $requestParameterValue) {
            if ($this->isFacetParameter($requestParameterName)) {
                $facetConfigTransfer = $this->getFacetConfigTransfer($requestParameterName, $requestParameterValue, $activeFacetConfigTransfers);

                $this->addFacetFilterToQuery($searchQuery, $facetConfigTransfer, $requestParameterValue);
            }
        }

        return $searchQuery;
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     *
     * @return bool
     */
    protected function isAllowedActiveFacetConfig(FacetConfigTransfer $facetConfigTransfer): bool
    {
        return in_array($facetConfigTransfer->getParameterName(), static::ALLOWED_DEFAULT_FACET_CONFIGS);
    }

    /**
     * @param string $requestParameterName
     *
     * @return bool
     */
    protected function isFacetParameter(string $requestParameterName): bool
    {
        return !in_array($requestParameterName, static::NON_FILTERING_GLUE_REQUEST_PARAMS, true);
    }

    /**
     * @param string $requestParameterName
     * @param mixed $requestParameterValue
     * @param array<\Generated\Shared\Transfer\FacetConfigTransfer> $activeFacetConfigTransfers
     *
     * @return \Generated\Shared\Transfer\FacetConfigTransfer
     */
    protected function getFacetConfigTransfer(
        string $requestParameterName,
        mixed $requestParameterValue,
        array $activeFacetConfigTransfers
    ): FacetConfigTransfer {
        foreach ($activeFacetConfigTransfers as $activeFacetConfigTransfer) {
            if ($activeFacetConfigTransfer->getParameterName() === $requestParameterName) {
                return $activeFacetConfigTransfer;
            }
        }

        if ($this->isRangeRequestParameter($requestParameterValue)) {
            return $this->getFactory()->createFacetConfigBuilder()->buildRangeFacetConfigTransfer($requestParameterName);
        }

        return $this->getFactory()->createFacetConfigBuilder()->buildValueFacetConfigTransfer($requestParameterName);
    }

    /**
     * @param mixed $requestParameterValue
     *
     * @return bool
     */
    protected function isRangeRequestParameter(mixed $requestParameterValue): bool
    {
        return isset($requestParameterValue[static::KEY_MIN])
            || isset($requestParameterValue[static::KEY_MAX]);
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param mixed|null $filterValue
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    protected function addFacetFilterToQuery(
        QueryInterface $searchQuery,
        FacetConfigTransfer $facetConfigTransfer,
        mixed $filterValue
    ): QueryInterface {
        if ($this->isFilterValueEmpty($filterValue)) {
            return $searchQuery;
        }

        return match ($facetConfigTransfer->getType()) {
            SharedSearchHttpConfig::FACET_TYPE_RANGE =>
                $this->addRangeFacetFilterToQuery($searchQuery, $facetConfigTransfer, $filterValue),
            SharedSearchHttpConfig::FACET_TYPE_PRICE_RANGE =>
                $this->addPriceRangeFacetFilterToQuery($searchQuery, $facetConfigTransfer, $filterValue),
            SharedSearchHttpConfig::FACET_TYPE_CATEGORY =>
                $this->addCategoryFacetFilterToQuery($searchQuery, $facetConfigTransfer, $filterValue),
            default => $this->addValueFacetFilterToQuery($searchQuery, $facetConfigTransfer, $filterValue),
        };
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param mixed $filterValue
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    protected function addRangeFacetFilterToQuery(
        QueryInterface $searchQuery,
        FacetConfigTransfer $facetConfigTransfer,
        mixed $filterValue
    ): QueryInterface {
        $parameterName = $facetConfigTransfer->getParameterName();

        $searchQueryRangeFacetFilterTransfer = (new SearchQueryRangeFacetFilterTransfer())->setFieldName($parameterName);

        if (isset($filterValue[SharedSearchHttpConfig::FACET_TYPE_RANGE_VALUE_MIN])) {
            $searchQueryRangeFacetFilterTransfer->setFrom($filterValue[SharedSearchHttpConfig::FACET_TYPE_RANGE_VALUE_MIN]);
        }

        if (isset($filterValue[SharedSearchHttpConfig::FACET_TYPE_RANGE_VALUE_MAX])) {
            $searchQueryRangeFacetFilterTransfer->setTo($filterValue[SharedSearchHttpConfig::FACET_TYPE_RANGE_VALUE_MAX]);
        }

        $searchQuery->getSearchQuery()->addSearchQueryFacetFilter($searchQueryRangeFacetFilterTransfer);

        return $searchQuery;
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param mixed $filterValue
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    protected function addValueFacetFilterToQuery(
        QueryInterface $searchQuery,
        FacetConfigTransfer $facetConfigTransfer,
        mixed $filterValue
    ): QueryInterface {
        $parameterName = $facetConfigTransfer->getParameterName();
        $searchQueryValueFacetFilterTransfer = (new SearchQueryValueFacetFilterTransfer())->setFieldName($parameterName);

        if ($facetConfigTransfer->getIsMultiValued() === true) {
            $filterValues = $this->ensureFilterValueIsArray($filterValue);

            foreach ($filterValues as $filterValue) {
                $searchQueryValueFacetFilterTransfer
                    ->addValue($this->transformFilterValueForSearch($facetConfigTransfer, $filterValue));
            }
        } else {
            $searchQueryValueFacetFilterTransfer
                ->addValue($this->transformFilterValueForSearch($facetConfigTransfer, $filterValue));
        }

        $searchQuery->getSearchQuery()->addSearchQueryFacetFilter($searchQueryValueFacetFilterTransfer);

        return $searchQuery;
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param mixed $filterValue
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    protected function addCategoryFacetFilterToQuery(
        QueryInterface $searchQuery,
        FacetConfigTransfer $facetConfigTransfer,
        mixed $filterValue
    ): QueryInterface {
        $categoryNode = $this->getFactory()->getCategoryStorageClient()->getCategoryNodeById(
            (int)$filterValue,
            $this->getFactory()->getLocaleClient()->getCurrentLocale(),
            $this->getFactory()->getStoreClient()->getCurrentStore()->getNameOrFail(),
        );

        $categoryNames = $this->getCategoryNames($categoryNode, []);

        $parameterName = $facetConfigTransfer->getParameterName();
        $searchQueryValueFacetFilterTransfer = (new SearchQueryValueFacetFilterTransfer())
            ->setFieldName($parameterName)
            ->setValues($categoryNames);

        $searchQuery->getSearchQuery()->addSearchQueryFacetFilter($searchQueryValueFacetFilterTransfer);

        return $searchQuery;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer $categoryNodeStorageTransfer
     * @param array<string> $categoryNames
     *
     * @return array<string>
     */
    protected function getCategoryNames(CategoryNodeStorageTransfer $categoryNodeStorageTransfer, array $categoryNames): array
    {
        $categoryNames[] = $categoryNodeStorageTransfer->getNameOrFail();

        foreach ($categoryNodeStorageTransfer->getChildren() as $childCategoryNodeStorageTransfer) {
            $categoryNames = $this->getCategoryNames($childCategoryNodeStorageTransfer, $categoryNames);
        }

        return $categoryNames;
    }

    /**
     * @param mixed $filterValue
     *
     * @return array<int, mixed>
     */
    protected function ensureFilterValueIsArray(mixed $filterValue): array
    {
        if (is_array($filterValue)) {
            return $filterValue;
        }

        return [$filterValue];
    }

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param mixed $filterValue
     *
     * @return mixed
     */
    protected function transformFilterValueForSearch(FacetConfigTransfer $facetConfigTransfer, mixed $filterValue)
    {
        $valueTransformerPlugin = $this->getFactory()
            ->createFacetValueTransformerFactory()
            ->createTransformer($facetConfigTransfer);

        if (!$valueTransformerPlugin) {
            return $filterValue;
        }

        return $valueTransformerPlugin->transformFromDisplay($filterValue);
    }

    /**
     * @param mixed $filterValue
     *
     * @return bool
     */
    protected function isFilterValueEmpty(mixed $filterValue): bool
    {
        if ($this->isFilterValueEmptyArray($filterValue)) {
            return true;
        }

        return !$filterValue && !is_numeric($filterValue);
    }

    /**
     * @param mixed $filterValue
     *
     * @return bool
     */
    protected function isFilterValueEmptyArray(mixed $filterValue): bool
    {
        if (is_array($filterValue) && !array_filter($filterValue, 'strlen')) {
            return true;
        }

        return false;
    }

    /**
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param mixed $filterValue
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    protected function addPriceRangeFacetFilterToQuery(QueryInterface $searchQuery, FacetConfigTransfer $facetConfigTransfer, mixed $filterValue)
    {
        $parameterName = $facetConfigTransfer->getParameterName();

        $searchQueryRangeFacetFilterTransfer = (new SearchQueryRangeFacetFilterTransfer())->setFieldName($parameterName);

        if (isset($filterValue[SharedSearchHttpConfig::FACET_TYPE_RANGE_VALUE_MIN])) {
            $minPrice = $this->convertFromFloatToInteger($filterValue[SharedSearchHttpConfig::FACET_TYPE_RANGE_VALUE_MIN]);
            $searchQueryRangeFacetFilterTransfer->setFrom((string)$minPrice);
        }

        if (isset($filterValue[SharedSearchHttpConfig::FACET_TYPE_RANGE_VALUE_MAX])) {
            $maxPrice = $this->convertFromFloatToInteger($filterValue[SharedSearchHttpConfig::FACET_TYPE_RANGE_VALUE_MAX]);
            $searchQueryRangeFacetFilterTransfer->setTo((string)$maxPrice);
        }

        $searchQuery->getSearchQuery()->addSearchQueryFacetFilter($searchQueryRangeFacetFilterTransfer);

        return $searchQuery;
    }

    /**
     * @param float|int|null $value
     *
     * @return int|null
     */
    protected function convertFromFloatToInteger(mixed $value): ?int
    {
        if ($value !== null) {
            return (int)$this->getFactory()->getMoneyClient()->fromFloat((float)$value)->requireAmount()->getAmount();
        }

        return null;
    }
}
