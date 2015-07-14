<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Catalog\Communication\Plugin\Router;

use Silex\Application;
use SprykerFeature\Shared\Application\Communication\ControllerServiceBuilder;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerEngine\Yves\Application\Business\Routing\AbstractRouter;
use SprykerEngine\Yves\Kernel\Communication\BundleControllerAction;
use SprykerEngine\Yves\Kernel\Communication\Controller\RouteNameResolver;
use SprykerEngine\Yves\Kernel\Communication\ControllerLocator;
use SprykerFeature\Yves\FrontendExporter\Communication\Mapper\UrlMapperInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class SearchRouter extends AbstractRouter
{

    /**
     * @var LocatorLocatorInterface
     */
    protected $locator;

    /**
     * @var UrlMapperInterface
     */
    protected $urlMapper;

    /**
     * @param Application $app
     * @param LocatorLocatorInterface $locator
     * @param UrlMapperInterface $urlMapper
     * @param null $sslEnabled
     */
    public function __construct(
        Application $app,
        LocatorLocatorInterface $locator,
        UrlMapperInterface $urlMapper,
        $sslEnabled = null
    ) {
        parent::__construct($app, $sslEnabled);
        $this->locator = $locator;
        $this->urlMapper = $urlMapper;
    }

    /**
     * {@inheritdoc}
     */
    public function generate($name, $parameters = [], $referenceType = self::ABSOLUTE_PATH)
    {
        if ('/search' === $name) {
            $request = ($this->app['request_stack']) ? $this->app['request_stack']->getCurrentRequest() : $this->app['request'];
            $requestParameters = $request->query->all();
            //if no page is provided we generate a url to change the filter and therefore want to reset the page
            //TODO @see SprykerFeature\Yves\Catalog\Business\Model\AbstractSearch Line 77
            //     same todo to put parameter name into constant
            if (!isset($parameters['page']) && isset($requestParameters['page'])) {
                unset($requestParameters['page']);
            }
            $pathInfo = $this->urlMapper->generateUrlFromParameters(
                $this->urlMapper->mergeParameters($requestParameters, $parameters)
            );
            $pathInfo = $name . $pathInfo;

            return $this->getUrlOrPathForType($pathInfo, $referenceType);
        }
        throw new RouteNotFoundException();
    }

    /**
     * {@inheritdoc}
     */
    public function match($pathinfo)
    {
        if ('/search' === $pathinfo) {
            $request = ($this->app['request_stack']) ? $this->app['request_stack']->getCurrentRequest() : $this->app['request'];
            $this->urlMapper->injectParametersFromUrlIntoRequest($pathinfo, $request);

            $bundleControllerAction = new BundleControllerAction('Catalog', 'Catalog', 'fulltextSearch');
            $controllerResolver = new ControllerLocator($bundleControllerAction);
            $routeResolver = new RouteNameResolver('catalog');

            $service = (new ControllerServiceBuilder())->createServiceForController(
                $this->app,
                $this->locator,
                $bundleControllerAction,
                $controllerResolver,
                $routeResolver
            );

            return [
                '_controller' => $service,
                '_route' => $routeResolver->resolve(),
            ];
        }
        throw new ResourceNotFoundException();
    }

}
