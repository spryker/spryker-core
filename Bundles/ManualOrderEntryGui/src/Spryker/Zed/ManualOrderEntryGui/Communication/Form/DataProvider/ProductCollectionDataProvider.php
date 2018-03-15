<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ManualOrderProductTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Product\ProductCollectionType;

class ProductCollectionDataProvider
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function getData(QuoteTransfer $quoteTransfer)
    {

//        for ($i = count($quoteTransfer->getManualOrderProducts()); $i<3; $i++) {
//            $quoteTransfer->addManualOrderProducts(new ManualOrderProductTransfer());
//        }

        if (count($quoteTransfer->getItems())) {
            $quoteTransfer->setManualOrderProducts(new ArrayObject());

        } else {
            $manualOrderProducts = new ArrayObject();
            for ($i = 0; $i<3; $i++) {
                $manualOrderProducts->append(new ManualOrderProductTransfer());
            }
            $quoteTransfer->setManualOrderProducts($manualOrderProducts);
        }

        return $quoteTransfer;
    }


    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            'data_class' => QuoteTransfer::class,
//            ProductsCollectionType::OPTION_ITEM_CLASS_COLLECTION => ItemTransfer::class,
            ProductCollectionType::OPTION_MANUAL_ORDER_PRODUCT_CLASS_COLLECTION => ManualOrderProductTransfer::class,
            'allow_extra_fields' => true,
            'csrf_protection' => false,
        ];
    }

}
