<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferValidity\Business\Switcher;

interface ProductOfferSwitcherInterface
{
    /**
     * @return void
     */
    public function updateProductOfferValidity(): void;
}
