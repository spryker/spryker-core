<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CmsPagesContentBannersResourceRelationship\Processor\Reader;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface ContentBannerReaderInterface
{
    /**
     * @param array<string> $cmsPageUuids
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return array<string, array<string, \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>>
     */
    public function getContentBannersResources(array $cmsPageUuids, RestRequestInterface $restRequest): array;
}
