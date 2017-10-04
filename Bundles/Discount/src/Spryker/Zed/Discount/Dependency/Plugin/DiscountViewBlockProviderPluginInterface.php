<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Dependency\Plugin;

interface DiscountViewBlockProviderPluginInterface
{

    /**
     * Specification:
     *
     * Provide url of controller action which will render block inside discount view page
     *
     * @api
     *
     * @return string
     */
    public function getUrl();

}
