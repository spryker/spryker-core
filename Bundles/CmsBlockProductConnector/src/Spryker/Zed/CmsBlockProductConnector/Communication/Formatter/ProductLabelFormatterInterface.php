<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Communication\Formatter;

interface ProductLabelFormatterInterface
{
    /**
     * @param string $productName
     * @param string $productSku
     *
     * @return string
     */
    public function format(string $productName, string $productSku): string;
}
