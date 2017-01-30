<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductConnector\Dependency\Facade;

use Spryker\Zed\Tax\Business\TaxFacadeInterface;

class TaxProductConnectorToTaxBridge implements TaxProductConnectorToTaxInterface
{

    /**
     * @var TaxFacadeInterface
     */
    protected $taxFacade;

    /**
     * @param TaxFacadeInterface $taxFacade
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

}
