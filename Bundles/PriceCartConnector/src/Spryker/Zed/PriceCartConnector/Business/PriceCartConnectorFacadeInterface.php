<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceCartConnector\Business;

use Generated\Shared\Transfer\ChangeTransfer;

interface PriceCartConnectorFacadeInterface
{

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer $change
     * @param null $grossPriceType
     *
     * @return \Generated\Shared\Transfer\ChangeTransfer
     */
    public function addGrossPriceToItems(ChangeTransfer $change, $grossPriceType = null);

}
