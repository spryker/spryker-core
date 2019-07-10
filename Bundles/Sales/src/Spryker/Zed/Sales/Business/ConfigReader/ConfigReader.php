<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\ConfigReader;

use Spryker\Zed\Sales\SalesConfig;

class ConfigReader implements ConfigReaderInterface
{
    /**
     * @var \Spryker\Zed\Sales\SalesConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Sales\SalesConfig $config
     */
    public function __construct(SalesConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getIdSalesOrderParameter(): string
    {
        return $this->config->getIdSalesOrderParameter();
    }
}
