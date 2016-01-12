<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductCartConnector\Dependency\Facade;

use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductCartConnectorToProductInterface
{

    /**
     * @param string $concreteSku
     *
     * @return ProductConcreteTransfer
     */
    public function getProductConcrete($concreteSku);

}
