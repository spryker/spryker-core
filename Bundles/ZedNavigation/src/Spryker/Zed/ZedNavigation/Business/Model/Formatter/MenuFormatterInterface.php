<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business\Model\Formatter;

interface MenuFormatterInterface
{
    /**
     * @param array $pages
     * @param string $pathInfo
     * @param bool $includeInvisible
     *
     * @return array
     */
    public function formatMenu(array $pages, $pathInfo, $includeInvisible = false);
}
