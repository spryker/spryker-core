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
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\Product\ItemCollectionType;

class ItemCollectionDataProvider implements FormDataProviderInterface
{
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

        $items = new ArrayObject();
        foreach ($transfer->getItems() as $itemTransfer) {
            $newItemTransfer = new ItemTransfer();
            $newItemTransfer->fromArray($itemTransfer->toArray(), true);

            $items->append($newItemTransfer);
        }
        $transfer->getManualOrder()->setItems($items);

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
            ItemCollectionType::OPTION_ITEM_CLASS_COLLECTION => ItemTransfer::class,
            ItemCollectionType::OPTION_ISO_CODE => $transfer->getCurrency()->getCode(),
            'allow_extra_fields' => true,
            'csrf_protection' => false,
        ];
    }
}
