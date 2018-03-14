<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ManualOrderProductTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Product\ProductsCollectionType;

class ProductDataProvider
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function getData(QuoteTransfer $quoteTransfer)
    {
        for ($i=0; $i<3; $i++) {
            $quoteTransfer->addManualOrderProducts(new ManualOrderProductTransfer());
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
            ProductsCollectionType::OPTION_DATA_CLASS_COLLECTION => ManualOrderProductTransfer::class,
            'allow_extra_fields' => true,
            'csrf_protection' => false,
        ];
    }

}
