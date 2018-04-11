<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ManualOrderEntryTransfer;
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
        if ($quoteTransfer->getManualOrderEntry() === null) {
            $quoteTransfer->setManualOrderEntry(new ManualOrderEntryTransfer());
        }

        $items = new ArrayObject();
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $newItemTransfer = new ItemTransfer();
            $newItemTransfer->fromArray($itemTransfer->toArray(), true);

            $items->append($newItemTransfer);
        }
        $quoteTransfer->getManualOrderEntry()->setItems($items);

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
            ItemCollectionType::OPTION_ITEM_CLASS_COLLECTION => ItemTransfer::class,
            ItemCollectionType::OPTION_ISO_CODE => $quoteTransfer->getCurrency()->getCode(),
            'allow_extra_fields' => true,
            'csrf_protection' => false,
        ];
    }
}
