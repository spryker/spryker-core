<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewSearch\Dependency\Facade;

use Generated\Shared\Transfer\DataMappingContextTransfer;

class ProductReviewSearchToSearchFacadeBridge implements ProductReviewSearchToSearchFacadeInterface
{
    /**
     * @var \Spryker\Zed\Search\Business\SearchFacadeInterface
     */
    protected $searchFacade;

    /**
     * @param \Spryker\Zed\Search\Business\SearchFacadeInterface $searchFacade
     */
    public function __construct($searchFacade)
    {
        $this->searchFacade = $searchFacade;
    }

    /**
     * Specification:
     * - Maps raw data to search data within a given context.
     *
     * @api
     *
     * @param array $data
     * @param \Generated\Shared\Transfer\DataMappingContextTransfer $dataMappingContextTransfer
     *
     * @return array
     */
    public function mapRawDataToSearchData(array $data, DataMappingContextTransfer $dataMappingContextTransfer): array
    {
        return $this->searchFacade->mapRawDataToSearchData($data, $dataMappingContextTransfer);
    }
}
