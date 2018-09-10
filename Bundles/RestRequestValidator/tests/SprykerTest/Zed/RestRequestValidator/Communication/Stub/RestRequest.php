<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\RestRequestValidator\Communication\Stub;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResource;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\Metadata;
use Spryker\Glue\GlueApplication\Rest\Request\Data\MetadataInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\Version;
use Spryker\Glue\GlueApplication\Rest\Request\RequestBuilder;
use SprykerTest\Shared\Kernel\Transfer\Fixtures\AbstractTransfer;
use Symfony\Component\HttpFoundation\Request;

class RestRequest
{
    /**
     * @param string $method
     * @param string $resourceType
     * @param \SprykerTest\Shared\Kernel\Transfer\Fixtures\AbstractTransfer|null $attributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface
     */
    public function createRestRequest(
        string $method = Request::METHOD_GET,
        string $resourceType = 'test',
        ?AbstractTransfer $attributesTransfer = null
    ): RestRequestInterface {
        $metadata = $this->createMetadata($method);

        $request = Request::create('/');
        if ($attributesTransfer !== null) {
            $request->attributes->set(
                RestResourceInterface::RESOURCE_DATA,
                ['attributes' => $attributesTransfer->toArray(true, true)]
            );
        }

        $restResource = new RestResource($resourceType, null, $attributesTransfer);

        return (new RequestBuilder($restResource))
            ->addMetadata($metadata)
            ->addHttpRequest($request)
            ->build();
    }

    /**
     * @param string $method
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\MetadataInterface
     */
    public function createMetadata(string $method = Request::METHOD_GET): MetadataInterface
    {
        $version = new Version(1, 1);

        $metadata = new Metadata(
            'json',
            'json',
            $method,
            'DE',
            true,
            $version
        );

        return $metadata;
    }
}
