<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\DataMapper\ProductReview;

use Generated\Shared\Search\ProductReviewIndexMap;
use Generated\Shared\Transfer\DataMappingContextTransfer;
use Generated\Shared\Transfer\ProductReviewSearchTransfer;
use Spryker\Zed\SearchElasticsearch\Business\DataMapper\DataMapperInterface;

class ProductReviewDataMapper implements DataMapperInterface
{
    protected const DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    protected const DATA_FIELD_STORE_NAME = 'store_name';
    protected const DATA_FIELD_ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    protected const DATA_FIELD_STORE_RATING = 'rating';
    protected const DATA_FIELD_STORE_CREATED_AT = 'created_at';

    /**
     * @param array $data
     * @param \Generated\Shared\Transfer\DataMappingContextTransfer $dataMappingContextTransfer
     *
     * @return array
     */
    public function mapRawDataToSearchData(array $data, DataMappingContextTransfer $dataMappingContextTransfer): array
    {
        return [
            ProductReviewIndexMap::STORE => $data[static::DATA_FIELD_STORE_NAME],
            ProductReviewIndexMap::ID_PRODUCT_ABSTRACT => $data[static::DATA_FIELD_ID_PRODUCT_ABSTRACT],
            ProductReviewIndexMap::RATING => $data[static::DATA_FIELD_STORE_RATING],
            ProductReviewIndexMap::SEARCH_RESULT_DATA => $this->getSearchResultData($data),
            ProductReviewIndexMap::CREATED_AT => $this->getFormattedCreatedAtDateTime($data[static::DATA_FIELD_STORE_CREATED_AT]),
        ];
    }

    /**
     * @param array $productReviewData
     *
     * @return array
     */
    protected function getSearchResultData(array $productReviewData): array
    {
        $productReviewTransfer = new ProductReviewSearchTransfer();
        $productReviewTransfer->fromArray($productReviewData, true);

        return $productReviewTransfer->modifiedToArray();
    }

    /**
     * @param string $createdAt
     *
     * @return string
     */
    protected function getFormattedCreatedAtDateTime(string $createdAt): string
    {
        return date(static::DATE_TIME_FORMAT, strtotime($createdAt));
    }
}
