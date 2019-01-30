<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Dependency\Service;


use Spryker\Service\UtilSanitize\UtilSanitizeService;
use Spryker\Service\UtilSanitize\UtilSanitizeServiceInterface;

class ProductAttributeToUtilSanitizeServiceBridge implements ProductAttributeToUtilSanitizeServiceInterface
{
    /**
     * @var \Spryker\Service\UtilSanitize\UtilSanitizeServiceInterface
     */
    protected $utilSanitizeService;

    /**
     * @param \Spryker\Service\UtilSanitize\UtilSanitizeServiceInterface $utilSanitizeService
     */
    public function __construct(UtilSanitizeServiceInterface $utilSanitizeService)
    {
        $this->utilSanitizeService = $utilSanitizeService;
    }

    /**
     * @param string|array $text
     * @param bool $double
     * @param null $charset
     */
    public function escapeHtml($text, $double = true, $charset = null)
    {
        $this->utilSanitizeService->escapeHtml($text, $double = true, $charset = null);
    }

}
