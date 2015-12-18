<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Locale\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\Locale\LocaleConfig;
use Spryker\Zed\Locale\Persistence\LocaleQueryContainer;

/**
 * @method LocaleConfig getConfig()
 * @method LocaleQueryContainer getQueryContainer()
 */
class LocaleCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return LocaleFacade
     */
    public function getInstallerFacade()
    {
        return $this->getLocator()->locale()->facade();
    }

}
