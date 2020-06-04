<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CmsPagesRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\CmsPageStorageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface CmsPageRestResponseBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsPageStorageTransfer $cmsPageStorageTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCmsPageRestResponse(CmsPageStorageTransfer $cmsPageStorageTransfer): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCmsPageEmptyRestResponse(): RestResponseInterface;
}
