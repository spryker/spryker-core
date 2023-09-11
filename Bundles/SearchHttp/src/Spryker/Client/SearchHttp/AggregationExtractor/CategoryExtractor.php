<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SearchHttp\AggregationExtractor;

use ArrayObject;
use Generated\Shared\Transfer\FacetConfigTransfer;
use Generated\Shared\Transfer\FacetSearchResultTransfer;
use Generated\Shared\Transfer\FacetSearchResultValueTransfer;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToCategoryStorageClientInterface;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToLocaleClientInterface;
use Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToStoreClientInterface;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

class CategoryExtractor implements AggregationExtractorInterface
{
    /**
     * @var \Generated\Shared\Transfer\FacetConfigTransfer
     */
    protected FacetConfigTransfer $facetConfigTransfer;

    /**
     * @var \Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToCategoryStorageClientInterface
     */
    protected SearchHttpToCategoryStorageClientInterface $categoryStorageClient;

    /**
     * @var \Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToLocaleClientInterface
     */
    protected SearchHttpToLocaleClientInterface $localeClient;

    /**
     * @var \Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToStoreClientInterface
     */
    protected SearchHttpToStoreClientInterface $storeClient;

    /**
     * @param \Generated\Shared\Transfer\FacetConfigTransfer $facetConfigTransfer
     * @param \Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToCategoryStorageClientInterface $categoryStorageClient
     * @param \Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToLocaleClientInterface $localeClient
     * @param \Spryker\Client\SearchHttp\Dependency\Client\SearchHttpToStoreClientInterface $storeClient
     */
    public function __construct(
        FacetConfigTransfer $facetConfigTransfer,
        SearchHttpToCategoryStorageClientInterface $categoryStorageClient,
        SearchHttpToLocaleClientInterface $localeClient,
        SearchHttpToStoreClientInterface $storeClient
    ) {
        $this->facetConfigTransfer = $facetConfigTransfer;
        $this->categoryStorageClient = $categoryStorageClient;
        $this->localeClient = $localeClient;
        $this->storeClient = $storeClient;
    }

    /**
     * @param array<string, mixed> $aggregations
     * @param array<string, mixed> $requestParameters
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function extractDataFromAggregations(array $aggregations, array $requestParameters): TransferInterface
    {
        $name = $this->facetConfigTransfer->getName();
        $parameterName = $this->facetConfigTransfer->getParameterName();

        $categoryNodeStorageTransfers = $this->categoryStorageClient->getCategories(
            $this->localeClient->getCurrentLocale(),
            $this->storeClient->getCurrentStore()->getNameOrFail(),
        );

        $facetResultValueTransfers = $this->extractFacetData($aggregations, $categoryNodeStorageTransfers);

        $facetResultTransfer = new FacetSearchResultTransfer();
        $facetResultTransfer
            ->setName($name)
            ->setValues($facetResultValueTransfers)
            ->setConfig(clone $this->facetConfigTransfer);

        if (isset($requestParameters[$parameterName])) {
            $facetResultTransfer->setActiveValue($requestParameters[$parameterName]);
        }

        return $facetResultTransfer;
    }

    /**
     * @param array<string, int> $aggregation
     * @param \ArrayObject<int, \Generated\Shared\Transfer\CategoryNodeStorageTransfer> $categoryNodeStorageTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\FacetSearchResultValueTransfer>
     */
    protected function extractFacetData(array $aggregation, ArrayObject $categoryNodeStorageTransfers): ArrayObject
    {
        $facetValues = new ArrayObject();

        $categoryNamesToIds = $this->getCategoryNamesToIds($categoryNodeStorageTransfers);

        foreach ($aggregation as $categoryName => $count) {
            $categoryId = $this->findCategoryIdByName($categoryName, $categoryNamesToIds);

            if ($categoryId) {
                $facetResultValueTransfer = (new FacetSearchResultValueTransfer())
                    ->setValue((string)$categoryId)
                    ->setDocCount($count);

                $facetValues->append($facetResultValueTransfer);
            }
        }

        return $facetValues;
    }

    /**
     * @param string $categoryName
     * @param array<string, int> $categoryNamesToIds
     *
     * @return int|null
     */
    protected function findCategoryIdByName(string $categoryName, array $categoryNamesToIds): ?int
    {
        return $categoryNamesToIds[$categoryName] ?? null;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\CategoryNodeStorageTransfer> $categoryNodeStorageTransfers
     *
     * @return array<string, int>
     */
    protected function getCategoryNamesToIds(ArrayObject $categoryNodeStorageTransfers): array
    {
        $result = [];

        foreach ($categoryNodeStorageTransfers as $categoryNodeStorageTransfer) {
            $result[$categoryNodeStorageTransfer->getName()] = $categoryNodeStorageTransfer->getIdCategoryOrFail();

            if ($categoryNodeStorageTransfer->getChildren()->count()) {
                $result = array_merge($result, $this->getCategoryNamesToIds($categoryNodeStorageTransfer->getChildren()));
            }
        }

        return $result;
    }
}
