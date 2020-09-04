<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Builder;

interface ProductConfigurationSessionKeyBuilderInterface
{
    /**
     * @param string $sku
     *
     * @return string
     */
    public function getProductConfigurationSessionKey(string $sku): string;
}
