<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentBannersRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\ContentBannerTypeTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface ContentBannerRestResponseBuilderInterface
{
    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addContentBannerIdNotSpecifiedError(): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addContentBannerNotFoundError(): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addContentTypeInvalidError(): RestResponseInterface;

    /**
     * @param \Generated\Shared\Transfer\ContentBannerTypeTransfer $contentBannerTypeTransfer
     * @param string $contentBannerKey
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createContentBannersRestResponse(
        ContentBannerTypeTransfer $contentBannerTypeTransfer,
        string $contentBannerKey
    ): RestResponseInterface;

    /**
     * @phpstan-param array<string, \Generated\Shared\Transfer\ContentBannerTypeTransfer> $contentBannerTypeTransfers
     *
     * @phpstan-return array<string, \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>
     *
     * @param \Generated\Shared\Transfer\ContentBannerTypeTransfer[] $contentBannerTypeTransfers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function createContentBannersRestResources(array $contentBannerTypeTransfers): array;
}
