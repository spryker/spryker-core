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
     * @var Store
     */
    protected $store;

    /**
     * @var TaxConfig
     */
    protected $config;

    /**
     * @param Store $store
     * @param TaxConfig $config
     */
    public function __construct(Store $store, TaxConfig $config)
    {
        $this->store = $store;
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getDefaultCountry()
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
