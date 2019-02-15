<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Dependency\Service;

class ProductAttributeToUtilSanitizeServiceBridge implements ProductAttributeToUtilSanitizeServiceInterface
{
    /**
     * @var \Spryker\Service\UtilSanitize\UtilSanitizeServiceInterface
     */
    protected $utilSanitizeService;

    /**
     * @param \Spryker\Service\UtilSanitize\UtilSanitizeServiceInterface $utilSanitizeService
     */
    public function __construct($utilSanitizeService)
    {
        $this->utilSanitizeService = $utilSanitizeService;
    }

    /**
     * @param array|string $text
     * @param bool $double
     * @param string|null $charset
     *
     * @return string|array
     */
    public function escapeHtml($text, $double = true, $charset = null)
    {
        return $this->utilSanitizeService->escapeHtml($text, $double, $charset);
    }
}
