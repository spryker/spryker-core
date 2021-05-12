<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceCartConnector\Business\Filter\Comparator;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\PriceCartConnector\Business\Exception\TransferPropertyNotFoundException;
use Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig;

class ItemComparator implements ItemComparatorInterface
{
    /**
     * @var \Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig
     */
    protected $priceCartConnectorConfig;

    /**
     * @param \Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig $priceCartConnectorConfig
     */
    public function __construct(PriceCartConnectorConfig $priceCartConnectorConfig)
    {
        $this->priceCartConnectorConfig = $priceCartConnectorConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemInCartTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @throws \Spryker\Zed\PriceCartConnector\Business\Exception\TransferPropertyNotFoundException
     *
     * @return bool
     */
    public function isSameItem(
        ItemTransfer $itemInCartTransfer,
        ItemTransfer $itemTransfer
    ): bool {
        $fields = $this->priceCartConnectorConfig->getItemFieldsForIsSameItemComparison();

        foreach ($fields as $fieldName) {
            if (!$itemTransfer->offsetExists($fieldName)) {
                throw new TransferPropertyNotFoundException(
                    sprintf(
                        'The property "%s" can\'t be found in ItemTransfer.',
                        $fieldName
                    )
                );
            }

            if ($itemInCartTransfer[$fieldName] !== $itemTransfer[$fieldName]) {
                return false;
            }
        }

        return true;
    }
}
