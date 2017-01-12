<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Dependency\Service;

use Spryker\Service\UtilSanitize\UtilSanitizeServiceInterface;

class SalesToUtilSanitizeBridge implements SalesToUtilSanitizeInterface
{

    /**
     * @var \Spryker\Service\UtilSanitize\UtilSanitizeServiceInterface
     */
    private $utilSanitizeService;

    /**
     * SalesToUtilSanitizeBridge constructor.
     *
     * @param \Spryker\Service\UtilSanitize\UtilSanitizeServiceInterface $utilSanitizeService
     */
    public function __construct(UtilSanitizeServiceInterface $utilSanitizeService)
    {
        $this->utilSanitizeService = $utilSanitizeService;
    }

    /**
     * @param string $text
     * @param bool $double
     * @param null $charset
     *
     * @return string
     */
    public function escapeHtml($text, $double = true, $charset = null)
    {
        return $this->utilSanitizeService->escapeHtml($text, $double, $charset);
    }

}
