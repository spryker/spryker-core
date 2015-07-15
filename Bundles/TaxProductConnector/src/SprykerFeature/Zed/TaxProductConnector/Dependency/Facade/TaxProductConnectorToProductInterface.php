<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\TaxProductConnector\Dependency\Facade;

interface TaxProductConnectorToProductInterface
{

    /**
     * @param int $idAbstractProduct
     */
    public function touchProductActive($idAbstractProduct);

}
