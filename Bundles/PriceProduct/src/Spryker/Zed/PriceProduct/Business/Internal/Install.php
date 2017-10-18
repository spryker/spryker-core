<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Internal;

use Spryker\Zed\PriceProduct\Business\Model\WriterInterface;
use Spryker\Zed\PriceProduct\PriceProductConfig;

class Install implements InstallInterface
{

    /**
     * @var \Spryker\Zed\PriceProduct\Business\Model\Writer
     */
    protected $writer;

    /**
     * @var \Spryker\Zed\PriceProduct\PriceProductConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\PriceProduct\Business\Model\WriterInterface $writer
     * @param \Spryker\Zed\PriceProduct\PriceProductConfig $config
     */
    public function __construct(WriterInterface $writer, PriceProductConfig $config)
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
