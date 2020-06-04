<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CmsPagesRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\CmsPageStorageTransfer;
use Generated\Shared\Transfer\RestCmsPagesAttributesTransfer;
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
     * @param \Generated\Shared\Transfer\CmsPageStorageTransfer $cmsPageStorageTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCmsPageRestResponse(CmsPageStorageTransfer $cmsPageStorageTransfer): RestResponseInterface
    {
        $cmsPageRestResource = $this->createCmsPageRestResource($cmsPageStorageTransfer);

        return $this->restResourceBuilder->createRestResponse()->addResource($cmsPageRestResource);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageStorageTransfer $cmsPageStorageTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createCmsPageRestResource(
        CmsPageStorageTransfer $cmsPageStorageTransfer
    ): RestResourceInterface {
        $restCmsPageAttributesTransfer = (new RestCmsPagesAttributesTransfer())
            ->fromArray($cmsPageStorageTransfer->toArray(), true);

        return $this->restResourceBuilder->createRestResource(
            CmsPagesRestApiConfig::RESOURCE_CMS_PAGES,
            $cmsPageStorageTransfer->getUuid(),
            $restCmsPageAttributesTransfer
        );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCmsPageEmptyRestResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse();
    }
}
