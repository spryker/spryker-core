<?php

namespace SprykerFeature\Yves\Catalog;

use Generated\Yves\Ide\FactoryAutoCompletion\Catalog;
use Silex\Application;
use SprykerEngine\Yves\Kernel\AbstractDependencyContainer;
use SprykerFeature\Yves\Catalog\Router\SearchRouter;

/**
 * Class CatalogDependencyContainer
 * @package SprykerFeature\Yves\Catalog
 */
class CatalogDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @var Catalog
     */
    protected $factory;

    /**
     * @param Application $app
     * @param bool $sslEnabled
     * @return SearchRouter
     */
    public function createSearchRouter(Application $app, $sslEnabled = false)
    {
        return $this->getFactory()->createRouterSearchRouter(
            $app,
            $this->getLocator(),
            $this->getLocator()->frontendExporter()->pluginUrlMapper()->createUrlMapper(),
            $sslEnabled
        );
    }

}
