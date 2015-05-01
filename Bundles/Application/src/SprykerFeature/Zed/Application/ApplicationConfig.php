<?php

namespace SprykerFeature\Zed\Application;

use Generated\Zed\Ide\AutoCompletion;
use SprykerFeature\Zed\Application\Business\Model\ApplicationCheckStep\AbstractApplicationCheckStep;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

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
}
