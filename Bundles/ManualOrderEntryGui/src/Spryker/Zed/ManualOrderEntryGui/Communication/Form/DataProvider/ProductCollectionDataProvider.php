<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\ManualOrderProductTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Product\ProductCollectionType;

class ProductCollectionDataProvider implements FormDataProviderInterface
{
    protected const NUMBER_PRODUCT_ROWS = 3;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function getData($quoteTransfer)
    {
        $manualOrderProducts = new ArrayObject();
        for ($i = 0; $i < static::NUMBER_PRODUCT_ROWS; $i++) {
            $manualOrderProducts->append(new ManualOrderProductTransfer());
        }
        $quoteTransfer->setManualOrderProducts($manualOrderProducts);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getOptions($quoteTransfer)
    {
        return [
            'data_class' => QuoteTransfer::class,
            ProductCollectionType::OPTION_MANUAL_ORDER_PRODUCT_CLASS_COLLECTION => ManualOrderProductTransfer::class,
            'allow_extra_fields' => true,
            'csrf_protection' => false,
        ];
    }
}
