<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Business\Model;

use Spryker\Zed\Tax\Dependency\Facade\TaxToStoreFacadeInterface;
use Spryker\Zed\Tax\TaxConfig;

class TaxDefault implements TaxDefaultInterface
{
    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @var \Spryker\Zed\Tax\TaxConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\Tax\Dependency\Facade\TaxToStoreFacadeInterface
     */
    protected TaxToStoreFacadeInterface $storeFacade;

    /**
     * @param \Spryker\Zed\Tax\Dependency\Facade\TaxToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\Tax\TaxConfig $config
     */
    public function __construct(TaxToStoreFacadeInterface $storeFacade, TaxConfig $config)
    {
        $this->config = $config;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @return string
     */
    public function getDefaultCountryIso2Code()
    {
        $countryList = $this->storeFacade->getCurrentStore()->getCountries();

        return reset($countryList);
    }

    /**
     * @return float
     */
    public function getDefaultTaxRate()
    {
        return $this->config->getDefaultTaxRate();
    }
}
