<?php

namespace SprykerFeature\Zed\Price\Business\Internal;

use SprykerFeature\Zed\Installer\Business\Model\AbstractInstaller;
use SprykerFeature\Zed\Price\Business\PriceFacade;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Price\Business\PriceSettings;

class Install extends AbstractInstaller
{

    /**
     * @var PriceFacade
     */
    protected $priceFacade;

    /**
     * @var PriceSettings
     */
    protected $settings;

    /**
     * @param PriceFacade $priceFacade
     * @param PriceSettings $settings
     */
    public function __construct(PriceFacade $priceFacade, PriceSettings $settings)
    {
        $this->priceFacade = $priceFacade;
        $this->settings = $settings;
    }

    public function install()
    {
        $this->createPriceType();
    }

    protected function createPriceType()
    {
        $this->priceFacade->createPriceType($this->settings->getPriceTypeDefaultName());
    }

}
