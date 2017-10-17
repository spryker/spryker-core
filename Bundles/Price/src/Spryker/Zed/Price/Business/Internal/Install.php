<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Price\Business\Internal;

use Spryker\Zed\Price\Business\Model\WriterInterface;
use Spryker\Zed\Price\PriceConfig;

class Install implements InstallInterface
{
    /**
     * @var \Spryker\Zed\Price\Business\Model\Writer
     */
    protected $writer;

    /**
     * @var \Spryker\Zed\Price\PriceConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Price\Business\Model\WriterInterface $writer
     * @param \Spryker\Zed\Price\PriceConfig $config
     */
    public function __construct(WriterInterface $writer, PriceConfig $config)
    {
        $this->writer = $writer;
        $this->config = $config;
    }

    /**
     * @return void
     */
    public function install()
    {
        $this->createPriceType();
    }

    /**
     * @return void
     */
    protected function createPriceType()
    {
        $this->writer->createPriceType($this->config->getPriceTypeDefaultName());
    }
}
