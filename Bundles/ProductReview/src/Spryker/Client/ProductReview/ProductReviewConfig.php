<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductReview;

use Generated\Shared\Transfer\PaginationConfigTransfer;
use Generated\Shared\Transfer\ProductReviewSearchConfigTransfer;
use Spryker\Client\Kernel\AbstractBundleConfig;

class ProductReviewConfig extends AbstractBundleConfig
{

    const PAGINATION_DEFAULT_ITEMS_PER_PAGE = 10;
    const PAGINATION_VALID_ITEMS_PER_PAGE = [
        10,
    ];

    /**
     * @return \Generated\Shared\Transfer\ProductReviewSearchConfigTransfer
     */
    public function getProductReviewSearchConfig()
    {
        $productReviewSearchConfigTransfer = new ProductReviewSearchConfigTransfer();
        $productReviewSearchConfigTransfer->setPaginationConfig($this->getPaginationConfig());

        return $productReviewSearchConfigTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\PaginationConfigTransfer
     */
    public function getPaginationConfig()
    {
        $paginationConfigTransfer = new PaginationConfigTransfer();
        $paginationConfigTransfer
            ->setParameterName('page')
            ->setItemsPerPageParameterName('ipp')
            ->setDefaultItemsPerPage(self::PAGINATION_DEFAULT_ITEMS_PER_PAGE)
            ->setValidItemsPerPageOptions(self::PAGINATION_VALID_ITEMS_PER_PAGE);

        return $paginationConfigTransfer;
    }

}
