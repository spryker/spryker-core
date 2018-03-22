<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Internal;

use Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceTypeWriterInterface;
use Spryker\Zed\PriceProduct\PriceProductConfig;

class Install implements InstallInterface
{
    /**
     * @var \Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceTypeWriterInterface
     */
    protected $priceTypeWriter;

    /**
     * @var \Spryker\Zed\PriceProduct\PriceProductConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\PriceProduct\Business\Model\PriceType\PriceTypeWriterInterface $priceTypeWriter
     * @param \Spryker\Zed\PriceProduct\PriceProductConfig $config
     */
    public function __construct(
        PriceTypeWriterInterface $priceTypeWriter,
        PriceProductConfig $config
    ) {
        $this->priceTypeWriter = $priceTypeWriter;
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
        $this->priceTypeWriter->createPriceType($this->config->getPriceTypeDefaultName());
    }
}
