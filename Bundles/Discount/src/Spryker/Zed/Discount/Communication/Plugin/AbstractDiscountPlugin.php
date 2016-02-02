<?php

namespace Spryker\Zed\Discount\Communication\Plugin;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Shared\Library\Currency\CurrencyManager;
use Spryker\Zed\Discount\Business\DiscountFacade;
use Spryker\Zed\Discount\Communication\DiscountCommunicationFactory;

/**
 * @method DiscountFacade getFacade()
 * @method DiscountCommunicationFactory getFactory()
 */
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
     * @return \Spryker\Shared\Library\Currency\CurrencyManager
     */
    protected function getCurrencyManager()
    {
        return CurrencyManager::getInstance();
    }

}
