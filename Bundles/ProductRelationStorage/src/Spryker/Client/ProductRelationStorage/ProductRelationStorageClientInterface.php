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
     *  - Only product relations assigned with passed $storeName will be returned.
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function findRelatedProducts($idProductAbstract, $localeName, string $storeName);

    /**
     * Specification:
     *  - Retrieves upselling products for the provided QuoteTransfer.
     *  - Maps raw product data from Storage to ProductViewTransfer for the provided locale.
     *  - Only product relations assigned with store of the Quote will be returned.
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
     *  - Only product relations with passed $storeName will be returned.
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param string $storeName
     *
     * @return int[]
     */
    public function findRelatedAbstractProductIds(int $idProductAbstract, string $storeName): array;

    /**
     * Specification:
     *  - Retrieves upselling abstract product ids for the provided QuoteTransfer.
     *  - Only product relations assigned with store of the Quote will be returned.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int[]
     */
    public function findUpSellingAbstractProductIds(QuoteTransfer $quoteTransfer): array;
}
