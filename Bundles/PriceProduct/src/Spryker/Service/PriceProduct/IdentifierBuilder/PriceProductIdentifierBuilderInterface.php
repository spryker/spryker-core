<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProduct\IdentifierBuilder;

use Generated\Shared\Transfer\PriceProductTransfer;

interface PriceProductIdentifierBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return string
     */
    public function buildPriceProductIdentifier(PriceProductTransfer $priceProductTransfer): string;
}
