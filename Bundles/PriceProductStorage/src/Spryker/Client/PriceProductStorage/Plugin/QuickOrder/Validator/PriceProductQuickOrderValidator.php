<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\Plugin\QuickOrder\Validator;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\QuickOrderTransfer;
use Spryker\Client\PriceProductStorage\Storage\PriceConcreteResolverInterface;

class PriceProductQuickOrderValidator implements PriceProductQuickOrderValidatorInterface
{
    protected const ERROR_NO_PRICE_PRODUCT = 'quick-order.upload-order.errors.upload-order-no-price-product';

    /**
     * @var \Spryker\Client\PriceProductStorage\Storage\PriceConcreteResolverInterface
     */
    protected $priceConcreteResolver;

    /**
     * @param \Spryker\Client\PriceProductStorage\Storage\PriceConcreteResolverInterface $priceConcreteResolver
     */
    public function __construct(PriceConcreteResolverInterface $priceConcreteResolver)
    {
        $this->priceConcreteResolver = $priceConcreteResolver;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderTransfer
     */
    public function validateQuickOrder(QuickOrderTransfer $quickOrderTransfer): QuickOrderTransfer
    {
        foreach ($quickOrderTransfer->getItems() as $orderItemTransfer) {
            $productConcreteTransfer = $orderItemTransfer->getProductConcrete();

            if (!$productConcreteTransfer || !$productConcreteTransfer->getIdProductConcrete()) {
                continue;
            }

            $priceProductFilterTransfer = (new PriceProductFilterTransfer())
                ->setQuantity($orderItemTransfer->getQuantity())
                ->setIdProduct($productConcreteTransfer->getIdProductConcrete())
                ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract());

            $priceProductTransfer = $this->priceConcreteResolver
                ->resolveCurrentProductPriceTransfer($priceProductFilterTransfer);

            if (!$priceProductTransfer->getPrice()) {
                $orderItemTransfer->addErrorMessage(static::ERROR_NO_PRICE_PRODUCT);
            }
        }

        return $quickOrderTransfer;
    }
}
