<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ManualOrderProductTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Product\ItemCollectionType;

class ItemCollectionDataProvider implements FormDataProviderInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getData($quoteTransfer)
    {
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
            ItemCollectionType::OPTION_ITEM_CLASS_COLLECTION => ManualOrderProductTransfer::class,
            ItemCollectionType::OPTION_ISO_CODE => $quoteTransfer->getCurrency()->getCode(),
            'allow_extra_fields' => true,
            'csrf_protection' => false,
        ];
    }
}
