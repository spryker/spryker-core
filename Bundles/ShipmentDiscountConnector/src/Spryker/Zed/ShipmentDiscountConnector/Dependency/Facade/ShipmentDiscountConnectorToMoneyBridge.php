<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentDiscountConnector\Dependency\Facade;

class ShipmentDiscountConnectorToMoneyBridge implements ShipmentDiscountConnectorToMoneyInterface
{

    /**
     * @var \Spryker\Zed\Money\Business\MoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @param \Spryker\Zed\Money\Business\MoneyFacadeInterface $moneyFacade
     */
    public function __construct($moneyFacade)
    {
        $this->moneyFacade = $moneyFacade;
    }

    /**
     * @param int $value
     *
     * @return float
     */
    public function convertIntegerToDecimal($value)
    {
        return $this->moneyFacade->convertIntegerToDecimal($value);
    }

}
