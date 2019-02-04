<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\Validator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ItemValidationResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Spryker\Client\PriceProductStorage\Storage\PriceConcreteResolverInterface;

class PriceProductItemValidator implements PriceProductItemValidatorInterface
{
    protected const MESSAGE_TYPE_ERROR = 'error';
    protected const ERROR_MESSAGE_NO_PRICE_PRODUCT = 'price_product.error.price_not_found';

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
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemValidationResponseTransfer
     */
    public function validate(ItemTransfer $itemTransfer): ItemValidationResponseTransfer
    {
        $productConcreteTransfer = $itemTransfer->getProductConcrete();
        $itemValidationResponseTransfer = new ItemValidationResponseTransfer();

        if (!$productConcreteTransfer || !$productConcreteTransfer->getIdProductConcrete()) {
            return $itemValidationResponseTransfer;
        }

        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setQuantity($itemTransfer->getQuantity())
            ->setIdProduct($productConcreteTransfer->getIdProductConcrete())
            ->setIdProductAbstract($productConcreteTransfer->getFkProductAbstract());

        $priceProductTransfer = $this->priceConcreteResolver
            ->resolveCurrentProductPriceTransfer($priceProductFilterTransfer);

        if (!$priceProductTransfer->getPrice()) {
            $itemValidationResponseTransfer->addMessage((new MessageTransfer())
                ->setType(static::MESSAGE_TYPE_ERROR)
                ->setValue(static::ERROR_MESSAGE_NO_PRICE_PRODUCT));
        }

        return $itemValidationResponseTransfer;
    }
}
