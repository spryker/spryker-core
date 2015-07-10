<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\FrontendExporter;

use Generated\Yves\Ide\FactoryAutoCompletion\FrontendExporter;
use SprykerFeature\Client\Catalog\Service\Model\FacetConfig;
use SprykerFeature\Client\FrontendExporter\Service\Matcher\UrlMatcher;
use SprykerFeature\Yves\FrontendExporter\Mapper\UrlMapper;
use SprykerFeature\Yves\FrontendExporter\Router\StorageRouter;
use SprykerEngine\Yves\Kernel\AbstractDependencyContainer;
use Silex\Application;

/**
 * @method FrontendExporter getFactory()
 */
class FrontendExporterDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @param Application $application
     * @param bool|null $sslEnabled
     *
     * @return StorageRouter
     */
    public function createStorageRouter(Application $application, $sslEnabled = null)
    {
        $storageRouter = $this->getFactory()->createRouterStorageRouter(
            $application,
            $this->createUrlMatcher(),
            $this->createUrlMapper(),
            $sslEnabled
        );

        $storageRouter->addResourceCreators($this->createSettings()->getResourceCreators());

        return $storageRouter;
    }

    /**
     * @return UrlMapper
     */
    public function createUrlMapper()
    {
        return $this->getFactory()->createMapperUrlMapper(
            $this->createFacetConfig()
        );
    }

    /**
     * @return FrontendExporterSettings
     */
    protected function createSettings()
    {
        return $this->getFactory()->createFrontendExporterSettings(
            $this->getLocator()
        );
    }

    /**
     * @return UrlMatcher
     */
    protected function createUrlMatcher()
    {
        $urlMatcher = $this->getLocator()->frontendExporter()
            ->client()
        ;

        return $urlMatcher;
    }

    /**
     * @return FacetConfig
     */
    protected function createFacetConfig()
    {
        return $this->getLocator()->catalog()->client()->createFacetConfig();
    }

}
