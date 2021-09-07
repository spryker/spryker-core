<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductStorage\Validator;

use Generated\Shared\Transfer\ItemValidationTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Spryker\Client\PriceProductStorage\Storage\PriceConcreteResolverInterface;

class PriceProductItemValidator implements PriceProductItemValidatorInterface
{
    /**
     * @var string
     */
    protected const MESSAGE_TYPE_ERROR = 'error';
    /**
     * @var string
     */
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
     * @param \Generated\Shared\Transfer\ItemValidationTransfer $itemValidationTransfer
     *
     * @return \Generated\Shared\Transfer\ItemValidationTransfer
     */
    public function validate(ItemValidationTransfer $itemValidationTransfer): ItemValidationTransfer
    {
        $itemValidationTransfer->requireItem();
        $itemTransfer = $itemValidationTransfer->getItem();

        if (!$itemTransfer->getId()) {
            return $itemValidationTransfer;
        }

        $itemTransfer->requireIdProductAbstract()
            ->requireQuantity();

        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setQuantity($itemTransfer->getQuantity())
            ->setIdProduct($itemTransfer->getId())
            ->setIdProductAbstract($itemTransfer->getIdProductAbstract());

        $priceProductTransfer = $this->priceConcreteResolver
            ->resolveCurrentProductPriceTransfer($priceProductFilterTransfer);

        if (!$priceProductTransfer->getPrice()) {
            $itemValidationTransfer->addMessage((new MessageTransfer())
                ->setType(static::MESSAGE_TYPE_ERROR)
                ->setValue(static::ERROR_MESSAGE_NO_PRICE_PRODUCT));
        }

        return $itemValidationTransfer;
    }
}
