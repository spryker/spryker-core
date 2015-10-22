<?php

namespace SprykerFeature\Zed\Discount\Communication\Plugin;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Shared\Library\Currency\CurrencyManager;

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
