<?php

namespace SprykerFeature\Zed\Setup\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

class SetupFacade extends AbstractFacade
{

    /**
     * @return \SprykerFeature_Zed_Setup_Business_Settings
     */
    public function createSettings()
    {
        return $this->factory->createSettings();
    }

    /**
     * @return array
     */
    public function getAllCronjobs()
    {
        return $this->factory->createModelCronjobs()->getCronjobs();
    }

    /**
     * @param string $what
     * @return string
     */
    public function getPhpInfo($what = null)
    {
        return $this->factory->createModelSystem()->getPhpInfo($what);
    }
}
