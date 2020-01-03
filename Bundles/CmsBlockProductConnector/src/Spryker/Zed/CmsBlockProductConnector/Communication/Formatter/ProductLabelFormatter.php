<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Communication\Formatter;

class ProductLabelFormatter implements ProductLabelFormatterInterface
{
    protected const FORMAT_LABEL = '%s (SKU: %s)';

    /**
     * @param string $productName
     * @param string $productSku
     *
     * @return string
     */
    public function format(string $productName, string $productSku): string
    {
        return sprintf(static::FORMAT_LABEL, $productName, $productSku);
    }
}
