<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductRelationStorage;

use Generated\Shared\Transfer\QuoteTransfer;

interface ProductRelationStorageClientInterface
{
    /**
     * Specification:
     *  - Retrieves related products for the provided product abstract ID.
     *  - Maps raw product data from Storage to ProductViewTransfer for the provided locale.
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function findRelatedProducts($idProductAbstract, $localeName);

    /**
     * Specification:
     *  - Retrieves upselling products for the provided QuoteTransfer.
     *  - Maps raw product data from Storage to ProductViewTransfer for the provided locale.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function findUpSellingProducts(QuoteTransfer $quoteTransfer, $localeName);

    /**
     * Specification:
     *  - Retrieves related abstract product ids for the provided product abstract ID.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function findRelatedAbstractProductIds(int $idProductAbstract): array;

    /**
     * Specification:
     *  - Retrieves upselling abstract product ids for the provided QuoteTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int[]
     */
    public function findUpSellingAbstractProductIds(QuoteTransfer $quoteTransfer): array;
}
