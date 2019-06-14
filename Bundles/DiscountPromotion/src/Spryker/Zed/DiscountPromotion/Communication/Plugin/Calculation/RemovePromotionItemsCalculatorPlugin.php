<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Communication\Plugin\Calculation;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Spryker\Zed\Calculation\Dependency\Plugin\CalculationPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionFacadeInterface getFacade()
 * @method \Spryker\Zed\DiscountPromotion\Communication\DiscountPromotionCommunicationFactory getFactory()
 * @method \Spryker\Zed\DiscountPromotion\DiscountPromotionConfig getConfig()
 * @method \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionQueryContainerInterface getQueryContainer()
 */
class RemovePromotionItemsCalculatorPlugin extends AbstractPlugin implements CalculationPluginInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $calculableObjectTransfer->setPromotionItems(new ArrayObject());
    }
}
