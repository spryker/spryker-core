<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CalculationCheckoutConnector\Dependency\Facade;

use Spryker\Zed\Calculation\Business\CalculationFacade;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;

class CalculationCheckoutConnectorToCalculationBridge implements CalculationCheckoutConnectorToCalculationInterface
{

    /**
     * @var \Spryker\Zed\Calculation\Business\CalculationFacadeInterface
     */
    protected $calculationFacade;

    /**
     * @param \Spryker\Zed\Calculation\Business\CalculationFacadeInterface $calculationFacade
     */
    public function __construct($calculationFacade)
    {
        $this->calculationFacade = $calculationFacade;
    }

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     *
     * @return \Spryker\Zed\Calculation\Business\Model\CalculableInterface
     */
    public function recalculate(CalculableInterface $calculableContainer)
    {
        return $this->calculationFacade->recalculate($calculableContainer);
    }

}
