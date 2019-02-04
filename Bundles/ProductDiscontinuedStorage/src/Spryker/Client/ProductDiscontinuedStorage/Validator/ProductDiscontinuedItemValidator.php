<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductDiscontinuedStorage\Validator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ItemValidationResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Client\ProductDiscontinuedStorage\Dependency\Client\ProductDiscontinuedStorageToLocaleClientInterface;
use Spryker\Client\ProductDiscontinuedStorage\Storage\ProductDiscontinuedStorageReaderInterface;

class ProductDiscontinuedItemValidator implements ProductDiscontinuedItemValidatorInterface
{
    protected const MESSAGE_TYPE_ERROR = 'error';
    protected const ERROR_MESSAGE_DISCONTINUED_PRODUCT = 'product_discontinued.message.product_discontinued';

    /**
     * @var \Spryker\Client\ProductDiscontinuedStorage\Storage\ProductDiscontinuedStorageReaderInterface
     */
    protected $productDiscontinuedStorageReader;

    /**
     * @var \Spryker\Client\ProductDiscontinuedStorage\Dependency\Client\ProductDiscontinuedStorageToLocaleClientInterface
     */
    protected $localeClient;

    /**
     * @param \Spryker\Client\ProductDiscontinuedStorage\Storage\ProductDiscontinuedStorageReaderInterface $productDiscontinuedStorageReader
     * @param \Spryker\Client\ProductDiscontinuedStorage\Dependency\Client\ProductDiscontinuedStorageToLocaleClientInterface $localeClient
     */
    public function __construct(ProductDiscontinuedStorageReaderInterface $productDiscontinuedStorageReader, ProductDiscontinuedStorageToLocaleClientInterface $localeClient)
    {
        $this->productDiscontinuedStorageReader = $productDiscontinuedStorageReader;
        $this->localeClient = $localeClient;
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

        if (!$productConcreteTransfer) {
            return $itemValidationResponseTransfer;
        }

        $productDiscontinuedTransfer = $this->productDiscontinuedStorageReader
            ->findProductDiscontinuedStorage($productConcreteTransfer->getSku(), $this->localeClient->getCurrentLocale());

        if ($productDiscontinuedTransfer) {
            $itemValidationResponseTransfer->addMessage((new MessageTransfer())
                ->setType(static::MESSAGE_TYPE_ERROR)
                ->setValue(static::ERROR_MESSAGE_DISCONTINUED_PRODUCT));
        }

        return $itemValidationResponseTransfer;
    }
}
