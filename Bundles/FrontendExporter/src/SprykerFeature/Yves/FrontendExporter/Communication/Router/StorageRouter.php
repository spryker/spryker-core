<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\FrontendExporter\Communication\Router;

use Silex\Application;
use SprykerFeature\Yves\FrontendExporter\Communication\Mapper\UrlMapperInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use SprykerFeature\Yves\FrontendExporter\Communication\Creator\ResourceCreatorInterface;
use SprykerFeature\Client\FrontendExporter\Service\Matcher\UrlMatcherInterface;
use SprykerEngine\Yves\Application\Business\Routing\AbstractRouter;

class StorageRouter extends AbstractRouter
{

    /**
     * @var ResourceCreatorInterface[]
     */
    protected $resourceCreators = [];

    /**
     * @var UrlMatcherInterface
     */
    protected $urlMatcher;

    /**
     * @var UrlMapperInterface
     */
    protected $urlMapper;

    /**
     * @param Application $app
     * @param UrlMatcherInterface $urlMatcher
     * @param UrlMapperInterface $urlMapper
     * @param bool|null $sslEnabled
     */
    public function __construct(
        Application $app,
        UrlMatcherInterface $urlMatcher,
        UrlMapperInterface $urlMapper,
        $sslEnabled = null
    ) {
        parent::__construct($app, $sslEnabled);
        $this->urlMatcher = $urlMatcher;
        $this->urlMapper = $urlMapper;
    }

    /**
     * {@inheritdoc}
     */
    public function generate($name, $parameters = [], $referenceType = self::ABSOLUTE_PATH)
    {
        if ($this->urlMatcher->matchUrl($name, $this->app['locale'])) {
            $request = $this->getRequest();
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
        if ($pathinfo !== '/') {
            $urlDetails = $this->urlMatcher->matchUrl($pathinfo, $this->app['locale']);
            if ($urlDetails) {
                foreach ($this->resourceCreators as $resourceCreator) {
                    if ($urlDetails['type'] === $resourceCreator->getType()) {
                        return $resourceCreator->createResource($this->app, $urlDetails['data']);
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

    /**
     * @param ResourceCreatorInterface[] $resourceCreators
     *
     * @return $this StorageRouter
     */
    public function addResourceCreators($resourceCreators)
    {
        foreach ($resourceCreators as $resourceCreator) {
            $this->addResourceCreator($resourceCreator);
        }

        return $this;
    }

    /**
     * @return Request
     */
    protected function getRequest()
    {
        if ($this->app['request_stack']) {
            return $this->app['request_stack']->getCurrentRequest();
        }

        return $this->app['request'];
    }

}
