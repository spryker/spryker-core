<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Price\Business\Internal;

use Spryker\Zed\Installer\Business\Model\AbstractInstaller;
use Spryker\Zed\Price\Business\PriceFacade;
use Spryker\Zed\Price\Business\PriceSettings;
use Spryker\Zed\Price\PriceConfig;

class Install extends AbstractInstaller
{

    /**
     * @var PriceFacade
     */
    protected $priceFacade;

    /**
     * @var PriceSettings
     */
    protected $config;

    /**
     * @param PriceFacade $priceFacade
     * @param PriceConfig $config
     */
    public function __construct(PriceFacade $priceFacade, PriceConfig $config)
    {
        $this->priceFacade = $priceFacade;
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
        $this->priceFacade->createPriceType($this->config->getPriceTypeDefaultName());
    }

}
