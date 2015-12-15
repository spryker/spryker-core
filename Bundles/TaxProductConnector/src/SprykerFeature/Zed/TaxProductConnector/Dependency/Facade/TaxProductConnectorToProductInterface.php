<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\TaxProductConnector\Dependency\Facade;

interface TaxProductConnectorToProductInterface
{

    /**
     * @param int $idAbstractProduct
     */
    public function touchProductActive($idAbstractProduct);

}
