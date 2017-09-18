<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Dependency\Facade;

class CurrencyToStoreBridge implements CurrencyToStoreInterface
{

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Shared\Kernel\Store $storeFacade
     */
    public function __construct($storeFacade)
    {
        $this->storeFacade = $storeFacade;
    }

    /**
     * @return string[]
     */
    public function getCurrencyIsoCodes()
    {
         return $this->storeFacade->getCurrencyIsoCodes();
    }

    /**
     * @return string
     */
    public function getCurrencyIsoCode()
    {
        return $this->storeFacade->getCurrencyIsoCode();
    }

}
