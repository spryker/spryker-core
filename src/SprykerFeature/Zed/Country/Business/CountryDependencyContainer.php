<?php

namespace SprykerFeature\Zed\Country\Business;

use Generated\Zed\Ide\AutoCompletion;
use Generated\Zed\Ide\FactoryAutoCompletion\CountryBusiness;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use Psr\Log\LoggerInterface;
use SprykerFeature\Zed\Country\Business\Cldr\CldrDataProviderInterface;
use SprykerFeature\Zed\Country\Business\Internal\Install;
use SprykerFeature\Zed\Country\Persistence\CountryQueryContainerInterface;

/**
 * @method CountryBusiness getFactory()
 */
class CountryDependencyContainer extends AbstractDependencyContainer
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
                $this->createSettings()->getCldrDir() . '/en/territories.json'
            ),
            $this->createCldrDataProvider(
                $this->createSettings()->getCldrDir() . '/supplemental/codeMappings.json'
            ),
            $this->createCldrDataProvider(
                $this->createSettings()->getCldrDir() . '/supplemental/postalCodeData.json'
            ),
            $this->createSettings()
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
            $this->createCountryQueryContainer(),
            $this->getLocator()
        );
    }

    /**
     * @return RegionManagerInterface
     */
    protected function createRegionManager()
    {
        return $this->getFactory()->createRegionManager(
            $this->createCountryQueryContainer(),
            $this->getLocator()
        );
    }

    /**
     * @return CountryQueryContainerInterface
     */
    protected function createCountryQueryContainer()
    {
        return $this->getLocator()->country()->queryContainer();
    }

    /**
     * @return CountrySettings
     */
    protected function createSettings()
    {
        return $this->getFactory()->createCountrySettings();
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
