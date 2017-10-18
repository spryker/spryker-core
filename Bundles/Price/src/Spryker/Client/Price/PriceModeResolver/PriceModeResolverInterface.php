<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Price\PriceModeResolver;

interface PriceModeResolverInterface
{
    /**
     * @return string
     */
    public function getCurrentPriceMode();
}
