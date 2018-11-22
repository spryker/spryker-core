<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionCartConnector\Dependency\Facade;

interface ProductOptionCartConnectorToProductOptionFacadeInterface
{
    /**
     * @param int $idProductOptionValueUsage
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer
     */
    public function getProductOptionValue($idProductOptionValueUsage);

    /**
     * @param int $idProductOptionValue
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer
     */
    public function getProductOptionValueById($idProductOptionValue);

    /**
     * @param int $idProductOptionValue
     *
     * @return bool
     */
    public function checkProductOptionGroupExistenceByProductOptionValueId(int $idProductOptionValue): bool;
}
