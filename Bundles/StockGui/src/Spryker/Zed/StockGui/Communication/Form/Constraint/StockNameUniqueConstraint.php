<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockGui\Communication\Form\Constraint;

use Spryker\Zed\StockGui\Dependency\Facade\StockGuiToStockFacadeInterface;
use Symfony\Component\Validator\Constraint;

class StockNameUniqueConstraint extends Constraint
{
    public const OPTION_STOCK_FACADE = 'stockFacade';

    /**
     * @var string
     */
    public $message = 'This name is already in use.';

    /**
     * @var \Spryker\Zed\StockGui\Dependency\Facade\StockGuiToStockFacadeInterface
     */
    protected $stockFacade;

    /**
     * @return \Spryker\Zed\StockGui\Dependency\Facade\StockGuiToStockFacadeInterface
     */
    public function getStockFacade(): StockGuiToStockFacadeInterface
    {
        return $this->stockFacade;
    }
}
