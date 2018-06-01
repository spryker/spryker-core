<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model\PriceData;

interface PriceDataChecksumGeneratorInterface
{
    /**
     * @param string $priceData
     *
     * @return string
     */
    public function generatePriceDataChecksum(string $priceData): string;
}
