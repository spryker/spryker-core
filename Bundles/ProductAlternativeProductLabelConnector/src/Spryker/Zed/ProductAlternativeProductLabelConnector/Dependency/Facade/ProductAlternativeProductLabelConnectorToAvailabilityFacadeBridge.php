<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeProductLabelConnector\Dependency\Facade;

class ProductAlternativeProductLabelConnectorToAvailabilityFacadeBridge implements ProductAlternativeProductLabelConnectorToAvailabilityFacadeInterface
{
    /**
     * @var \Spryker\Zed\Availability\Business\AvailabilityFacadeInterface
     */
    protected $availabilityFacade;

    /**
     * @param \Spryker\Zed\Availability\Business\AvailabilityFacadeInterface $availabilityFacade
     */
    public function __construct($availabilityFacade)
    {
        $this->availabilityFacade = $availabilityFacade;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return bool
     */
    public function isProductConcreteIsAvailable(int $idProductConcrete): bool
    {
        return $this->availabilityFacade->isProductConcreteIsAvailable($idProductConcrete);
    }

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function calculateStockForProduct(string $sku): bool
    {
        return (bool)$this->availabilityFacade->calculateStockForProduct($sku);
    }
}
