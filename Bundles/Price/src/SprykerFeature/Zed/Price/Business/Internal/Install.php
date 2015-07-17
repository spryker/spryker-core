<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Price\Business\Internal;

use SprykerFeature\Zed\Installer\Business\Model\AbstractInstaller;
use SprykerFeature\Zed\Price\Business\PriceFacade;
use SprykerFeature\Zed\Price\Business\PriceSettings;
use SprykerFeature\Zed\Price\PriceConfig;

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

    public function install()
    {
        $this->createPriceType();
    }

    protected function createPriceType()
    {
        $this->priceFacade->createPriceType($this->config->getPriceTypeDefaultName());
    }

}
