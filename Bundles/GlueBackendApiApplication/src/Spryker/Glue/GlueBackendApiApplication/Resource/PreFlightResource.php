<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\Resource;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Spryker\Glue\GlueApplication\Plugin\GlueApplication\Backend\AbstractResourcePlugin;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationConfig getConfig()
 */
class PreFlightResource extends AbstractResourcePlugin implements ResourceInterface
{
    /**
     * @var string
     */
    protected const HEADER_ACCESS_CONTROL_ALLOW_METHODS = 'Access-Control-Allow-Methods';

    /**
     * @var string
     */
    protected const HEADER_ACCESS_CONTROL_ALLOW_HEADERS = 'Access-Control-Allow-Headers';

    /**
     * @var \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface
     */
    protected ResourceInterface $resource;

    /**
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceInterface $resource
     */
    public function __construct(ResourceInterface $resource)
    {
        $this->resource = $resource;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return callable():\Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getResource(GlueRequestTransfer $glueRequestTransfer): callable
    {
        $resource = $this->resource;

        return function () use ($resource): GlueResponseTransfer {
            $declaredMethods = array_keys(array_filter($resource->getDeclaredMethods()->toArray()));

            foreach ($declaredMethods as $methodIndex => $declaredMethod) {
                if ($declaredMethod === 'get_collection') {
                    unset($declaredMethods[$methodIndex]);
                    $declaredMethods[] = 'get';
                }
            }

            $declaredMethods[] = Request::METHOD_OPTIONS;

            return (new GlueResponseTransfer())
                ->setHttpStatus(Response::HTTP_NO_CONTENT)
                ->addMeta(static::HEADER_ACCESS_CONTROL_ALLOW_METHODS, strtoupper(implode(', ', array_unique($declaredMethods))))
                ->addMeta(static::HEADER_ACCESS_CONTROL_ALLOW_HEADERS, implode(', ', $this->getConfig()->getCorsAllowedHeaders()));
        };
    }

    /**
     * @return string
     */
    public function getController(): string
    {
        return $this->resource->getController();
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->resource->getType();
    }

    /**
     * @return \Generated\Shared\Transfer\GlueResourceMethodCollectionTransfer
     */
    public function getDeclaredMethods(): GlueResourceMethodCollectionTransfer
    {
        return $this->resource->getDeclaredMethods();
    }
}
