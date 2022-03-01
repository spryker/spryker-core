<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Router;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Spryker\Glue\GlueApplication\GlueApplicationConfig;
use Spryker\Glue\GlueApplication\Resource\MissingResource;
use Spryker\Glue\GlueApplication\Router\Uri\UriParserInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use Symfony\Component\HttpFoundation\Request;

class ResourceRouter implements ResourceRouterInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Router\Uri\UriParserInterface
     */
    protected UriParserInterface $uriParser;

    /**
     * @var \Spryker\Glue\GlueApplication\Router\RequestResourcePluginFilterInterface
     */
    protected RequestResourcePluginFilterInterface $requestResourcePluginFilter;

    /**
     * @param \Spryker\Glue\GlueApplication\Router\Uri\UriParserInterface $uriParser
     * @param \Spryker\Glue\GlueApplication\Router\RequestResourcePluginFilterInterface $requestResourcePluginFilter
     */
    public function __construct(
        UriParserInterface $uriParser,
        RequestResourcePluginFilterInterface $requestResourcePluginFilter
    ) {
        $this->uriParser = $uriParser;
        $this->requestResourcePluginFilter = $requestResourcePluginFilter;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface> $resourcePlugins
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface
     */
    public function matchRequest(GlueRequestTransfer $glueRequestTransfer, array $resourcePlugins): ResourceInterface
    {
        $resources = $this->uriParser->parse($glueRequestTransfer->getPath());

        if ($resources === null) {
            return new MissingResource(
                GlueApplicationConfig::ERROR_CODE_RESOURCE_NOT_FOUND,
                GlueApplicationConfig::ERROR_MESSAGE_RESOURCE_NOT_FOUND,
            );
        }
        $mainResource = array_pop($resources);
        $mainGlueResourceTransfer = (new GlueResourceTransfer())
            ->setResourceName($mainResource['type'])
            ->setId($mainResource['id'])
            ->setMethod(strtolower($glueRequestTransfer->getMethod()));
        if ($mainGlueResourceTransfer->getMethod() === strtolower(Request::METHOD_GET) && !$mainGlueResourceTransfer->getId()) {
            $mainGlueResourceTransfer->setMethod('getCollection');
        }
        $glueRequestTransfer->setResource($mainGlueResourceTransfer);

        foreach ($resources as $resource) {
            $glueRequestTransfer->addParentResource(
                $resource['type'],
                (new GlueResourceTransfer())
                    ->setResourceName($resource['type'])
                    ->setId($resource['id'])
                    ->setMethod(strtolower($glueRequestTransfer->getMethod())),
            );
        }

        return $this->loadResource($glueRequestTransfer, $resourcePlugins);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface> $resourcePlugins
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface
     */
    protected function loadResource(GlueRequestTransfer $glueRequestTransfer, array $resourcePlugins): ResourceInterface
    {
        $resourcePlugin = $this->requestResourcePluginFilter->filterResourcePlugins($glueRequestTransfer, $resourcePlugins);

        if (!$resourcePlugin) {
            return new MissingResource(
                GlueApplicationConfig::ERROR_CODE_RESOURCE_NOT_FOUND,
                GlueApplicationConfig::ERROR_MESSAGE_RESOURCE_NOT_FOUND,
            );
        }

        $glueResourceMethodCollectionTransfer = $resourcePlugin->getDeclaredMethods();

        if (
            !$glueResourceMethodCollectionTransfer->offsetGet($glueRequestTransfer->getResource()->getMethod()) &&
            $glueRequestTransfer->getMethod() !== Request::METHOD_OPTIONS
        ) {
            return new MissingResource(
                GlueApplicationConfig::ERROR_CODE_METHOD_NOT_FOUND,
                GlueApplicationConfig::ERROR_MESSAGE_METHOD_NOT_FOUND,
            );
        }

        return $resourcePlugin;
    }
}
