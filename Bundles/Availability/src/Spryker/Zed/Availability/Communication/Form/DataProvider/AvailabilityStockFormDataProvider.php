<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Communication\Form\DataProvider;

use Generated\Shared\Transfer\AvailabilityStockTransfer;

class AvailabilityStockFormDataProvider
{

    const DATA_CLASS = 'data_class';

    /**
     * @var \Generated\Shared\Transfer\AvailabilityStockTransfer
     */
    protected $availabilityStockTransfer;

    /**
     * @param \Generated\Shared\Transfer\AvailabilityStockTransfer $availabilityStockTransfer
     */
    public function __construct(AvailabilityStockTransfer $availabilityStockTransfer)
    {
        $this->availabilityStockTransfer = $availabilityStockTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\AvailabilityStockTransfer
     */
    public function getData()
    {
        return $this->availabilityStockTransfer;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            static::DATA_CLASS => AvailabilityStockTransfer::class,
        ];
    }

}
