<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Form\Provider;

use Generated\Shared\Transfer\PriceProductScheduleListTransfer;

class PriceProductScheduleListFormDataProvider
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleListTransfer $priceProductScheduleListTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListTransfer
     */
    public function getData(PriceProductScheduleListTransfer $priceProductScheduleListTransfer): PriceProductScheduleListTransfer
    {
        return $priceProductScheduleListTransfer;
    }
}
