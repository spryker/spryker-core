<?php

namespace SprykerFeature\Zed\Installer\Business;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\Locator;

abstract class InstallerSettings
{

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
     * @return array
     */
    abstract public function getInstallerStack();
}
