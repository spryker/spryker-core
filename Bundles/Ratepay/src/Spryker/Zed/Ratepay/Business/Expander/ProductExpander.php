<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Expander;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\Ratepay\Dependency\Facade\RatepayToProductInterface;

class ProductExpander implements ProductExpanderInterface
{

    /**
     * @var \Spryker\Zed\Ratepay\Dependency\Facade\RatepayToProductBridge
     */
    protected $ratepayToProductBridge;

    /**
     * @param \Spryker\Zed\Ratepay\Dependency\Facade\RatepayToProductInterface $ratepayToProductBridge
     */
    public function __construct(RatepayToProductInterface $ratepayToProductBridge)
    {
        $this->ratepayToProductBridge = $ratepayToProductBridge;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $change
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItems(CartChangeTransfer $change)
    {
        foreach ($change->getItems() as $cartItem) {
            $productConcreteTransfer = $this->ratepayToProductBridge->getProductConcrete($cartItem->getSku());

            foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttribute) {
                $attr = $localizedAttribute->getAttributes();
                if (isset($attr['short_description'])) {
                    $cartItem->setDescriptionAddition($attr['short_description']);
                }
                if (isset($attr['long_description'])) {
                    $cartItem->setDescription($attr['long_description']);
                }
            }
        }

        return $change;
    }

}
