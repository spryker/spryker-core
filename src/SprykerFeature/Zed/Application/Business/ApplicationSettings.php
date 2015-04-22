<?php

namespace SprykerFeature\Zed\Application\Business;

use Generated\Zed\Ide\AutoCompletion;
use SprykerFeature\Zed\Application\Business\Model\ApplicationCheckStep\AbstractApplicationCheckStep;
use SprykerEngine\Zed\Kernel\Locator;

class ApplicationSettings
{
    const MAX_LEVEL_COUNT = 3;

    /**
     * @var AutoCompletion
     */
    protected $locator;

    /**
     * @param Locator $locator
     */
    public function __construct(Locator $locator)
    {
        $this->locator = $locator;
    }

    /**
     * @return AbstractApplicationCheckStep[]
     */
    public function getCheckSteps()
    {
        return [
            $this->locator->application()->consoleApplicationCheckStepDeleteDatabase(),
            $this->locator->application()->consoleApplicationCheckStepDeleteGeneratedDirectory(),
            $this->locator->application()->consoleApplicationCheckStepSetupInstall(),
            $this->locator->application()->consoleApplicationCheckStepCodeCeption(),
            $this->locator->application()->consoleApplicationCheckStepInstallDemoData(),
            $this->locator->application()->consoleApplicationCheckStepExportKeyValue(),
            $this->locator->application()->consoleApplicationCheckStepExportSearch(),
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
