<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Communication\Plugin\Calculator;

use Generated\Shared\Transfer\DiscountTransfer;
use Spryker\Shared\Library\Currency\CurrencyManager;
use Spryker\Zed\Discount\Business\DiscountFacade;
use Spryker\Zed\Discount\Business\Model\DiscountableInterface;

/**
 * @method DiscountFacade getFacade()
 */
class Fixed extends AbstractCalculator
{

    /**
     * @param DiscountableInterface[] $discountableObjects
     * @param int $number
     *
     * @return float
     */
    public function calculate(array $discountableObjects, $number)
    {
        return $this->getFacade()->calculateFixed($discountableObjects, $number);
    }

    /**
     * @param int $value
     *
     * @return int
     */
    public function transformForPersistence($value)
    {
        return $this->getCurrencyManager()->convertDecimalToCent($value);
    }

    /**
     * @param int $value
     *
     * @return float
     */
    public function transformFromPersistence($value)
    {
        return $this->getCurrencyManager()->convertCentToDecimal($value);
    }

    /**
     * @param DiscountTransfer $discountTransfer
     *
     * @return string
     */
    public function getFormattedAmount(DiscountTransfer $discountTransfer)
    {
        $currencyManager = CurrencyManager::getInstance();
        $discountAmount = $currencyManager->convertCentToDecimal($discountTransfer->getAmount());

        return $currencyManager->format($discountAmount);
    }

}
