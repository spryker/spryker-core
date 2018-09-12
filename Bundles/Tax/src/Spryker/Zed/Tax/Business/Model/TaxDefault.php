<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Business\Model;

use Spryker\Shared\Kernel\Store;
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
     * @param \Spryker\Shared\Kernel\Store $store
     * @param \Spryker\Zed\Tax\TaxConfig $config
     */
    public function __construct(Store $store, TaxConfig $config)
    {
        $this->store = $store;
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getDefaultCountryIso2Code()
    {
        return $this->store->getCurrentCountry();
    }

    /**
     * @return float
     */
    public function getDefaultTaxRate()
    {
        return $this->config->getDefaultTaxRate();
    }
}
