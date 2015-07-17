<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Country\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\CountryBusiness;
use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Country\Business\Cldr\CldrDataProviderInterface;
use SprykerFeature\Zed\Country\Business\Internal\Install;
use SprykerFeature\Zed\Country\CountryConfig;
use SprykerFeature\Zed\Country\Persistence\CountryQueryContainer;

/**
 * @method CountryBusiness getFactory()
 * @method CountryConfig getConfig()
 * @method CountryQueryContainer getQueryContainer()
 */
class CountryDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @param MessengerInterface $messenger
     *
     * @return Install
     */
    public function createInstaller(MessengerInterface $messenger)
    {
        $installer = $this->getFactory()->createInternalInstall(
            $this->createCountryManager(),
            $this->createRegionManager(),
            $this->createCldrDataProvider(
                $this->getConfig()->getCldrDir() . '/en/territories.json'
            ),
            $this->createCldrDataProvider(
                $this->getConfig()->getCldrDir() . '/supplemental/codeMappings.json'
            ),
            $this->createCldrDataProvider(
                $this->getConfig()->getCldrDir() . '/supplemental/postalCodeData.json'
            ),
            $this->getConfig()
        );

        $installer->setMessenger($messenger);

        return $installer;
    }

    /**
     * @return CountryManagerInterface
     */
    public function createCountryManager()
    {
        return $this->getFactory()->createCountryManager(
            $this->getQueryContainer()
        );
    }

    /**
     * @return RegionManagerInterface
     */
    protected function createRegionManager()
    {
        return $this->getFactory()->createRegionManager(
            $this->getQueryContainer()
        );
    }

    /**
     * @param string $filePath
     *
     * @return CldrDataProviderInterface
     */
    protected function createCldrDataProvider($filePath)
    {
        return $this->getFactory()->createCldrJsonFileCldrDataProvider(
            $filePath
        );
    }

}
