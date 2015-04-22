<?php


namespace SprykerFeature\Zed\Country\Communication;


use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use SprykerFeature\Zed\Country\Business\CountryFacade;

class CountryDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @return CountryFacade
     */
    public function getInstallerFacade()
    {
        return $this->getLocator()->country()->facade();
    }
}
