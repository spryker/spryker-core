<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Communication\Plugin;

use Spryker\Zed\Api\ApiConfig;

class WhitelistServerVariableFilterStrategy implements ServerVariableFilterStrategyInterface
{
    /**
     * @param array $serverData
     * @param \Spryker\Zed\Api\ApiConfig $config
     *
     * @return array
     */
    public function filter(array $serverData, ApiConfig $config): array
    {
        return array_intersect_key($serverData, array_flip($config->getServerVariablesWhitelist()));
    }
}
