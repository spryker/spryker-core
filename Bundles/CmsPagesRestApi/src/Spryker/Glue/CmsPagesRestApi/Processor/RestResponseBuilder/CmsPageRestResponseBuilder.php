<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CmsPagesRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\RestCmsPagesAttributesTransfer;
use Generated\Shared\Transfer\RestCmsPageTransfer;
use Spryker\Glue\CmsPagesRestApi\CmsPagesRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

class CmsPageRestResponseBuilder implements CmsPageRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(RestResourceBuilderInterface $restResourceBuilder)
    {
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @param string $cmsPageUuid
     * @param \Generated\Shared\Transfer\RestCmsPageTransfer $restCmsPageTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCmsPageRestResponse(string $cmsPageUuid, RestCmsPageTransfer $restCmsPageTransfer): RestResponseInterface
    {
        $cmsPageRestResource = $this->createCmsPageRestResource($cmsPageUuid, $restCmsPageTransfer);

        return $this->restResourceBuilder->createRestResponse()->addResource($cmsPageRestResource);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCmsPageEmptyRestResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse();
    }

    /**
     * @param \Generated\Shared\Transfer\RestCmsPagesAttributesTransfer $restSearchAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCmsPageCollectionRestResponse(RestCmsPagesAttributesTransfer $restSearchAttributesTransfer): RestResponseInterface
    {
        $response = $this->restResourceBuilder->createRestResponse($restSearchAttributesTransfer->getPagination()->getNumFound());

        foreach ($restSearchAttributesTransfer->getRestCmsPages() as $restCmsPageTransfer) {
            $response->addResource($this->createCmsPageRestResource($restCmsPageTransfer->getUuid(), $restCmsPageTransfer));
        }

        return $response;
    }

    /**
     * @param string $cmsPageUuid
     * @param \Generated\Shared\Transfer\RestCmsPageTransfer $restCmsPageTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createCmsPageRestResource(
        string $cmsPageUuid,
        RestCmsPageTransfer $restCmsPageTransfer
    ): RestResourceInterface {
        return $this->restResourceBuilder->createRestResource(
            CmsPagesRestApiConfig::RESOURCE_CMS_PAGES,
            $cmsPageUuid,
            $restCmsPageTransfer
        );
    }
}
