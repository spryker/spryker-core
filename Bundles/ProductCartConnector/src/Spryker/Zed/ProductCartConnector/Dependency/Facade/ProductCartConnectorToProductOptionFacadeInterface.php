<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCartConnector\Dependency\Facade;

interface ProductCartConnectorToProductOptionFacadeInterface
{
    /**
     * @param int $idProductOptionValue
     *
     * @return \Generated\Shared\Transfer\ProductOptionTransfer
     */
    public function getProductOptionValueById($idProductOptionValue);
}
