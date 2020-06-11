<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CmsPagesRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\RestCmsPagesAttributesTransfer;
use Generated\Shared\Transfer\RestCmsPageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface CmsPageRestResponseBuilderInterface
{
    /**
     * @param string $cmsPageUuid
     * @param \Generated\Shared\Transfer\RestCmsPageTransfer $cmsPageStorageTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCmsPageRestResponse(string $cmsPageUuid, RestCmsPageTransfer $cmsPageStorageTransfer): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCmsPageEmptyRestResponse(): RestResponseInterface;

    /**
     * @param \Generated\Shared\Transfer\RestCmsPagesAttributesTransfer $restSearchAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCmsPageCollectionRestResponse(RestCmsPagesAttributesTransfer $restSearchAttributesTransfer): RestResponseInterface;
}
