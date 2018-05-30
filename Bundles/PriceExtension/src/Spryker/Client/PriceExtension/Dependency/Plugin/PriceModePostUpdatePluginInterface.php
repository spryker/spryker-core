<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceExtension\Dependency\Plugin;

interface PriceModePostUpdatePluginInterface
{
    /**
     *  Specification:
     *   - Plugin executed after price mode is changed.
     *
     * @api
     *
     * @param string $priceMode
     *
     * @return void
     */
    public function execute(string $priceMode): void;
}
