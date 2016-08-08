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
     * @api
     *
     * @param int $idAbstractProduct
     * @param int $localeName
     *
     * @return \Generated\Shared\Transfer\ProductOptionGroupsTransfer
     */
    public function getProductOptions($idAbstractProduct, $localeName);

}
