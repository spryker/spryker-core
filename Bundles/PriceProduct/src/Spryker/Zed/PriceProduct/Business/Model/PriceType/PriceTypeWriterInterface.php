<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model\PriceType;

interface PriceTypeWriterInterface
{
    /**
     * @param string $name
     *
     * @return int
     */
    public function createPriceType($name);
}
