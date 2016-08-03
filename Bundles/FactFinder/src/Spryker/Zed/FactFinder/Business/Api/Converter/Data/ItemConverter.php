<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business\Api\Converter\Data;

use FACTFinder\Data\Item;
use Generated\Shared\Transfer\FactFinderDataItemTransfer;
use Spryker\Zed\FactFinder\Business\Api\Converter\BaseConverter;

class ItemConverter extends BaseConverter
{

    /**
     * @var \FACTFinder\Data\Item
     */
    protected $item;

    /**
     * @param \FACTFinder\Data\Item $item
     */
    public function setItem(Item $item)
    {
        $this->item = $item;
    }

    /**
     * @return \Generated\Shared\Transfer\FactFinderDataItemTransfer
     */
    public function convert()
    {
        $factFinderDataItemTransfer = new FactFinderDataItemTransfer();

        return $factFinderDataItemTransfer;
    }

}
