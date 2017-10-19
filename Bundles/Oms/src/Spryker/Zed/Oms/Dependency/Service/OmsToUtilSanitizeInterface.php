<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\Oms\Dependency\Service;

interface OmsToUtilSanitizeInterface
{
    /**
     * @param string $text
     * @param bool $double
     *
     * @return string
     */
    public function escapeHtml($text, $double = true);
}
