<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ManualOrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Product\ProductCollectionType;

class ProductCollectionDataProvider implements FormDataProviderInterface
{
    /**
     * @var int
     */
    protected const NUMBER_PRODUCT_ROWS = 3;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $transfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData($transfer): QuoteTransfer
    {
        if ($transfer->getManualOrder() === null) {
            $transfer->setManualOrder(new ManualOrderTransfer());
        }

        $products = new ArrayObject();
        for ($i = 0; $i < static::NUMBER_PRODUCT_ROWS; $i++) {
            $products->append(new ItemTransfer());
        }
        $transfer->getManualOrder()->setProducts($products);

        return $transfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $transfer
     *
     * @return array<string, mixed>
     */
    public function getOptions($transfer): array
    {
        return [
            'data_class' => QuoteTransfer::class,
            ProductCollectionType::OPTION_PRODUCT_CLASS_COLLECTION => ItemTransfer::class,
            'allow_extra_fields' => true,
            'csrf_protection' => false,
        ];
    }
}
