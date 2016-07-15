<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Client\ProductOption;
use Generated\Shared\Transfer\ProductOptionGroupsTransfer;

/**
 * @method \Spryker\Client\ProductOption\ProductOptionFactory getFactory()
 */
interface ProductOptionClientInterface
{
    /**
     * @param int $idAbstractProduct
     * @param int $localeName
     *
     * @return ProductOptionGroupsTransfer
     */
    public function getProductOptions($idAbstractProduct, $localeName);
}
