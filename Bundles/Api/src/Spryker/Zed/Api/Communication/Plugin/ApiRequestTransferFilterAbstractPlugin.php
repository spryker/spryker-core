<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Communication\Plugin;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;

abstract class ApiRequestTransferFilterAbstractPlugin extends AbstractPlugin implements ApiRequestTransferFilterPluginInterface
{
    /**
     * @param array $data
     * @param array $allowedKeys
     *
     * @return array
     */
    protected function doFilter(array $data, array $allowedKeys): array
    {
        return array_intersect_key($data, array_flip($allowedKeys));
    }
}
