<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CmsPagesContentBannersResourceRelationship\Dependency\RestApiResource;

class CmsPagesContentBannersResourceRelationshipToContentBannersRestApiResourceBridge implements CmsPagesContentBannersResourceRelationshipToContentBannersRestApiResourceInterface
{
    /**
     * @var \Spryker\Glue\ContentBannersRestApi\ContentBannersRestApiResourceInterface
     */
    protected $contentBannersRestApiResource;

    /**
     * @param \Spryker\Glue\ContentBannersRestApi\ContentBannersRestApiResourceInterface $contentBannersRestApiResource
     */
    public function __construct($contentBannersRestApiResource)
    {
        $this->contentBannersRestApiResource = $contentBannersRestApiResource;
    }

    /**
     * @phpstan-return array<string, \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>
     *
     * @param string[] $contentBannerKeys
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function getContentBannersByKeys(array $contentBannerKeys, string $localeName): array
    {
        return $this->contentBannersRestApiResource->getContentBannersByKeys($contentBannerKeys, $localeName);
    }
}
