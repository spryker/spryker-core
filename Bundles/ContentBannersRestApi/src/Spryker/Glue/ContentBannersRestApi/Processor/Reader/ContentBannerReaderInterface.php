<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentBannersRestApi\Processor\Reader;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface ContentBannerReaderInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getContentBannerById(RestRequestInterface $restRequest): RestResponseInterface;

    /**
     * @param array<string> $contentBannerKeys
     * @param string $localeName
     *
     * @return array<string, \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>
     */
    public function getContentBannersResources(array $contentBannerKeys, string $localeName): array;
}
