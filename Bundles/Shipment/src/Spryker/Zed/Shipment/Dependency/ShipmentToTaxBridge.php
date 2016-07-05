<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Dependency;

class ShipmentToTaxBridge implements ShipmentToTaxInterface
{

    /**
     * @var \Spryker\Zed\Tax\Business\TaxFacade
     */
    protected $taxFacade;

    /**
     * ShipmentToTaxBridge constructor.
     *
     * @param \Spryker\Zed\Tax\Business\TaxFacade $taxFacade
     */
    public function __construct($taxFacade)
    {
        $this->taxFacade = $taxFacade;
    }

    /**
     * @return string
     */
    public function getDefaultTaxCountryIso2Code()
    {
        return $this->taxFacade->getDefaultTaxCountryIso2Code();
    }

    /**
     * @return float
     */
    public function getDefaultTaxRate()
    {
        return $this->taxFacade->getDefaultTaxRate();
    }

    /**
     * @return \Generated\Shared\Transfer\TaxSetCollectionTransfer
     */
    public function getTaxSets()
    {
        return $this->taxFacade->getTaxSets();
    }

}
