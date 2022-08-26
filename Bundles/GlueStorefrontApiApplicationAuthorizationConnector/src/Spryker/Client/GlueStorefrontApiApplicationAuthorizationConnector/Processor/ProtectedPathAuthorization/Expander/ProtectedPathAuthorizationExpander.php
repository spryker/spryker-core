<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\GlueStorefrontApiApplicationAuthorizationConnector\Processor\ProtectedPathAuthorization\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Generated\Shared\Transfer\CustomRoutesContextTransfer;
use Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer;
use Generated\Shared\Transfer\ResourceContextTransfer;
use Generated\Shared\Transfer\RouteTransfer;
use Spryker\Client\GlueStorefrontApiApplicationAuthorizationConnector\Processor\ProtectedPathAuthorization\Checker\ProtectedPathAuthorizationCheckerInterface;
use Symfony\Component\HttpFoundation\Request;

class ProtectedPathAuthorizationExpander implements ProtectedPathAuthorizationExpanderInterface
{
    /**
     * @var string
     */
    protected const METHOD = '_method';

    /**
     * @var string
     */
    protected const FORMAT_RESOURCE_PATH = '/%s';

    /**
     * @var string
     */
    protected const FORMAT_RESOURCE_PATH_ID = '/%s/id';

    /**
     * @var string
     */
    protected const METHOD_GET_COLLECTION = 'getCollection';

    /**
     * @var array<string>
     */
    protected const RESOURCE_ID_METHODS = [
        'get', 'patch', 'delete',
    ];

    /**
     * @var \Spryker\Client\GlueStorefrontApiApplicationAuthorizationConnector\Processor\ProtectedPathAuthorization\Checker\ProtectedPathAuthorizationCheckerInterface
     */
    protected $protectedPathAuthorizationChecker;

    /**
     * @param \Spryker\Client\GlueStorefrontApiApplicationAuthorizationConnector\Processor\ProtectedPathAuthorization\Checker\ProtectedPathAuthorizationCheckerInterface $protectedPathAuthorizationChecker
     */
    public function __construct(ProtectedPathAuthorizationCheckerInterface $protectedPathAuthorizationChecker)
    {
        $this->protectedPathAuthorizationChecker = $protectedPathAuthorizationChecker;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
     *
     * @return \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer
     */
    public function expandApiApplicationSchemaContext(
        ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
    ): ApiApplicationSchemaContextTransfer {
        /** @var \ArrayObject<int, \Generated\Shared\Transfer\CustomRoutesContextTransfer> $customRoutesContextTransfers */
        $customRoutesContextTransfers = new ArrayObject();
        foreach ($apiApplicationSchemaContextTransfer->getCustomRoutesContexts() as $customRoutesContext) {
            $customRoutesContextTransfers[] = $this->expandCustomRoutesContext($customRoutesContext);
        }
        $apiApplicationSchemaContextTransfer->setCustomRoutesContexts($customRoutesContextTransfers);

        /** @var \ArrayObject<int, \Generated\Shared\Transfer\ResourceContextTransfer> $resourceContextTransfers */
        $resourceContextTransfers = new ArrayObject();
        foreach ($apiApplicationSchemaContextTransfer->getResourceContexts() as $resourceContext) {
            $resourceContextTransfers[] = $this->expandResource($resourceContext);
        }
        $apiApplicationSchemaContextTransfer->setResourceContexts($resourceContextTransfers);

        return $apiApplicationSchemaContextTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceContextTransfer $resourceContextTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceContextTransfer
     */
    protected function expandResource(ResourceContextTransfer $resourceContextTransfer): ResourceContextTransfer
    {
        $isProtected = $this->expandGlueResourceMethodCollection($resourceContextTransfer->getDeclaredMethodsOrFail(), $resourceContextTransfer->getResourceTypeOrFail());

        return $resourceContextTransfer->setDeclaredMethodsOrFail($isProtected);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomRoutesContextTransfer $customRoutesContextTransfer
     *
     * @return \Generated\Shared\Transfer\CustomRoutesContextTransfer
     */
    protected function expandCustomRoutesContext(CustomRoutesContextTransfer $customRoutesContextTransfer): CustomRoutesContextTransfer
    {
        if (!isset($customRoutesContextTransfer->getDefaults()[static::METHOD])) {
            return $customRoutesContextTransfer->setIsProtectedOrFail(false);
        }

        $routeTransfer = (new RouteTransfer())
            ->setRoute($customRoutesContextTransfer->getPathOrFail())
            ->setMethod($customRoutesContextTransfer->getDefaults()[static::METHOD]);

        $isProtected = $this->protectedPathAuthorizationChecker->isProtected($routeTransfer);

        return $customRoutesContextTransfer->setIsProtectedOrFail($isProtected);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer $glueResourceMethodCollectionTransfer
     * @param string $resourceType
     *
     * @return \Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer
     */
    protected function expandGlueResourceMethodCollection(
        GlueResourceMethodCollectionTransfer $glueResourceMethodCollectionTransfer,
        string $resourceType
    ): GlueResourceMethodCollectionTransfer {
        foreach ($glueResourceMethodCollectionTransfer->modifiedToArrayNotRecursiveCamelCased() as $httpMethod => $glueResourceMethodConfigurationTransfer) {
            $path = $this->generatePath($httpMethod, $resourceType);
            $httpMethod = $this->getHttpMethodByResourceMethodCollectionIdentifier($httpMethod);

            $routeTransfer = (new RouteTransfer())
                ->setRoute($path)
                ->setMethod($httpMethod);

            $glueResourceMethodConfigurationTransfer->setIsProtected(
                $this->protectedPathAuthorizationChecker->isProtected($routeTransfer),
            );
        }

        return $glueResourceMethodCollectionTransfer;
    }

    /**
     * @param string $httpMethod
     *
     * @return string
     */
    protected function getHttpMethodByResourceMethodCollectionIdentifier(string $httpMethod): string
    {
        if ($httpMethod === static::METHOD_GET_COLLECTION) {
            $httpMethod = Request::METHOD_GET;
        }

        return $httpMethod;
    }

    /**
     * @param string $httpMethod
     * @param string $resourceType
     *
     * @return string
     */
    protected function generatePath(string $httpMethod, string $resourceType): string
    {
        return in_array($httpMethod, static::RESOURCE_ID_METHODS) ?
            sprintf(static::FORMAT_RESOURCE_PATH_ID, $resourceType) :
            sprintf(static::FORMAT_RESOURCE_PATH, $resourceType);
    }
}
