<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOption;

/**
 * @method \Spryker\Client\ProductOption\ProductOptionFactory getFactory()
 */
interface ProductOptionClientInterface
{
    /**
     * Specification:
     * - Reads product options from storage.
     * - Selects store price according the current price mode, and current currency.
     * - Removes options without price.
     * - Removes product option groups without product options.
     *
     * @api
     *
     * @param int $idAbstractProduct
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\StorageProductOptionGroupCollectionTransfer
     */
    public function getProductOptions($idAbstractProduct, $localeName);
}
