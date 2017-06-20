<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Dependency\Facade;

use Generated\Shared\Transfer\MoneyTransfer;
use Spryker\Zed\Money\Business\MoneyFacadeInterface;

class ProductLabelGuiToMoneyBridge implements ProductLabelGuiToMoneyInterface
{

    /**
     * @var \Spryker\Zed\Money\Business\MoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @param \Spryker\Zed\Money\Business\MoneyFacadeInterface $moneyFacade
     */
    public function __construct(MoneyFacadeInterface $moneyFacade)
    {
        $this->moneyFacade = $moneyFacade;
    }

    /**
     * @param int $price
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    public function fromInteger($price)
    {
        return $this
            ->moneyFacade
            ->fromInteger($price);
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyTransfer $moneyTransfer
     *
     * @return string
     */
    public function formatWithSymbol(MoneyTransfer $moneyTransfer)
    {
        return $this
            ->moneyFacade
            ->formatWithSymbol($moneyTransfer);
    }

}
