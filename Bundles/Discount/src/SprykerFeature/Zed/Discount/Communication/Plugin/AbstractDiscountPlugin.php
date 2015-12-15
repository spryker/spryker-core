<?php

namespace Spryker\Zed\Discount\Communication\Plugin;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Shared\Library\Currency\CurrencyManager;

class AbstractDiscountPlugin extends AbstractPlugin
{

    /**
     * @param string $value
     *
     * @return string
     */
    public function transformForPersistence($value)
    {
        return $value;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function transformFromPersistence($value)
    {
        return $value;
    }

    /**
     * @return CurrencyManager
     */
    protected function getCurrencyManager()
    {
        return CurrencyManager::getInstance();
    }

}
