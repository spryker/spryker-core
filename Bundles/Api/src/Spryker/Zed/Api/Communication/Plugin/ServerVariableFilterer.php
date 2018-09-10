<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Communication\Plugin;

use Spryker\Zed\Api\ApiConfig;

class ServerVariableFilterer
{
    /**
     * @var \Spryker\Zed\Api\Communication\Plugin\ServerVariableFilterStrategyInterface
     */
    protected $serverVariableFilterStrategy;

    /**
     * @var \Spryker\Zed\Api\ApiConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Api\Communication\Plugin\ServerVariableFilterStrategyInterface $serverVariableFilterStrategy
     * @param \Spryker\Zed\Api\ApiConfig $config
     */
    public function __construct(ServerVariableFilterStrategyInterface $serverVariableFilterStrategy, ApiConfig $config)
    {
        $this->serverVariableFilterStrategy = $serverVariableFilterStrategy;
        $this->config = $config;
    }

    /**
     * @param array $serverData
     *
     * @return array
     */
    public function filter(array $serverData): array
    {
        return $this->serverVariableFilterStrategy->filter($serverData, $this->config);
    }
}
