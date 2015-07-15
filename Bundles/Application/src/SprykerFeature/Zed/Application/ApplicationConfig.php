<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application;

use SprykerFeature\Zed\Application\Business\Model\ApplicationCheckStep\AbstractApplicationCheckStep;
use SprykerEngine\Zed\Kernel\AbstractBundleConfig;
use SprykerFeature\Shared\Application\ApplicationConfig as SharedApplicationConfig;

class ApplicationConfig extends AbstractBundleConfig
{

    const MAX_LEVEL_COUNT = 3;

    /**
     * @return AbstractApplicationCheckStep[]
     */
    public function getCheckSteps()
    {
        return [
            $this->getLocator()->application()->consoleApplicationCheckStepDeleteDatabase(),
            $this->getLocator()->application()->consoleApplicationCheckStepDeleteGeneratedDirectory(),
            $this->getLocator()->application()->consoleApplicationCheckStepSetupInstall(),
            $this->getLocator()->application()->consoleApplicationCheckStepCodeCeption(),
            $this->getLocator()->application()->consoleApplicationCheckStepInstallDemoData(),
            $this->getLocator()->application()->consoleApplicationCheckStepExportKeyValue(),
            $this->getLocator()->application()->consoleApplicationCheckStepExportSearch(),
        ];
    }

    /**
     * @return int
     */
    public function getMaxMenuLevelCount()
    {
        return self::MAX_LEVEL_COUNT;
    }

    /**
     * @return array
     */
    public function getNavigationSchemaPathPattern()
    {
        return [
            APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/*/src/*/Zed/*/Communication',
        ];
    }

    /**
     * @return string
     */
    public function getNavigationSchemaFileNamePattern()
    {
        return 'navigation.xml';
    }

    /**
     * @return string
     */
    public function getRootNavigationSchema()
    {
        return APPLICATION_ROOT_DIR . '/config/Zed/' . $this->getNavigationSchemaFileNamePattern();
    }

    /**
     * @return string
     */
    public function getCacheFile()
    {
        return APPLICATION_ROOT_DIR . '/src/Generated/navigation.cache';
    }

    /**
     * @return string
     */
    public function isNavigationCacheEnabled()
    {
        return $this->get(SharedApplicationConfig::NAVIGATION_CACHE_ENABLED);
    }

}
