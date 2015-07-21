<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Catalog\Communication;

use Silex\Application;
use SprykerEngine\Yves\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Yves\FrontendExporter\Communication\Mapper\UrlMapperInterface;

class CatalogDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return UrlMapperInterface
     */
    public function createUrlMapper()
    {
        return $this->getLocator()->frontendExporter()->pluginUrlMapper()->createUrlMapper();
    }

    /**
     * @return Application
     */
    public function createApplication()
    {
        return $this->getLocator()->application()->pluginPimple()->getApplication();
    }

}
