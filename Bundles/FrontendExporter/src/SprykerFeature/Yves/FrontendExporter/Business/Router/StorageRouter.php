<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\FrontendExporter\Business\Router;

use SprykerFeature\Shared\Library\Storage\Adapter\KeyValue\ReadInterface;
use SprykerEngine\Yves\Application\Business\Routing\AbstractRouter;
use SprykerFeature\Yves\Catalog\Business\Model\UrlMapper;
use SprykerFeature\Yves\FrontendExporter\Business\Creator\ResourceCreatorInterface;
use SprykerFeature\Yves\FrontendExporter\Business\Matcher\UrlMatcherInterface;
use Silex\Application;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

/**
 * Class StorageRouter
 */
class StorageRouter extends AbstractRouter
{

    /**
     * @var ResourceCreatorInterface[]
     */
    private $resourceCreators = [];

    /**
     * @var UrlMatcherInterface
     */
    private $urlMatcher;

    /**
     * @var ReadInterface
     */
    private $keyValueReader;

    /**
     * @param Application         $app
     * @param UrlMatcherInterface $urlMatcher
     * @param ReadInterface       $keyValueReader
     * @param null                $sslEnabled
     */
    public function __construct(
        Application $app,
        UrlMatcherInterface $urlMatcher,
        ReadInterface $keyValueReader,
        $sslEnabled = null
    )
    {
        parent::__construct($app, $sslEnabled);
        $this->urlMatcher = $urlMatcher;
        $this->keyValueReader = $keyValueReader;
    }

    /**
     * {@inheritdoc}
     */
    public function generate($name, $parameters = [], $referenceType = self::ABSOLUTE_PATH)
    {
        if ($this->urlMatcher->matchUrl($name, $this->app['locale'])) {
            $facetConfig = $this->factory->createCatalogModelFacetConfig();
            $request = ($this->app['request_stack']) ? $this->app['request_stack']->getCurrentRequest() : $this->app['request'];
            $requestParameters = $request->query->all();
            //if no page is provided we generate a url to change the filter and therefore want to reset the page
            //TODO @see SprykerFeature\Yves\Catalog\Business\Model\AbstractSearch Line 77
            //     same todo to put parameter name into constant
            if (!isset($parameters['page']) && isset($requestParameters['page'])) {
                unset($requestParameters['page']);
            }
            $pathInfo = UrlMapper::generateUrlFromParameters(
                UrlMapper::mergeParameters($requestParameters, $parameters, $facetConfig),
                $facetConfig
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
        if ($pathinfo !== '/') {
            $urlDetails = $this->urlMatcher->matchUrl($pathinfo, $this->app['locale']);
            if ($urlDetails) {
                $data = $this->keyValueReader->get($urlDetails['reference_key']);
                if ($data) {
                    foreach ($this->resourceCreators as $resourceCreator) {
                        if ($urlDetails['type'] === $resourceCreator->getType()) {
                            return $resourceCreator->createResource($this->app, $data);
                        }
                    }
                }
            }
        }
        throw new ResourceNotFoundException();
    }

    /**
     * @param ResourceCreatorInterface $resourceCreator
     *
     * @return $this StorageRouter
     */
    public function addResourceCreator(ResourceCreatorInterface $resourceCreator)
    {
        $this->resourceCreators[] = $resourceCreator;

        return $this;
    }

}
