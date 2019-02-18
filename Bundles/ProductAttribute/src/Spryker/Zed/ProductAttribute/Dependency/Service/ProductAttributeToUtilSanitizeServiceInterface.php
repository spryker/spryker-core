<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Dependency\Service;

interface ProductAttributeToUtilSanitizeServiceInterface
{
    /**
     * @param array|string $text
     * @param bool $double
     * @param string|null $charset
     *
     * @return string|array
     */
    public function escapeHtml($text, $double = true, $charset = null);
}
