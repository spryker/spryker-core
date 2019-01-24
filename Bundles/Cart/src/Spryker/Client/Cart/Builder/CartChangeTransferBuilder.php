<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cart\Builder;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Client\Cart\Expander\CartChangeItemExpanderInterface;

class CartChangeTransferBuilder implements CartChangeTransferBuilderInterface
{
    /**
     * @var \Spryker\Client\Cart\Expander\CartChangeItemExpanderInterface
     */
    protected $cartChangeItemExpander;

    /**
     * @param \Spryker\Client\Cart\Expander\CartChangeItemExpanderInterface $cartChangeItemExpander
     */
    public function __construct(
        CartChangeItemExpanderInterface $cartChangeItemExpander
    ) {
        $this->cartChangeItemExpander = $cartChangeItemExpander;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function build(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        foreach ($cartChangeTransfer->getItems() as $cartChangeItemTransfer) {
            if ($cartChangeItemTransfer->getProductConcrete() === null) {
                continue;
            }

            $this->cartChangeItemExpander->expand($cartChangeItemTransfer);
        }

        return $cartChangeTransfer;
    }
}
