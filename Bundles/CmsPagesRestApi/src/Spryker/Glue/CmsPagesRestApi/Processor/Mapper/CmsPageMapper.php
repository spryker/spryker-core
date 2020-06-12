<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CmsPagesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestCmsPageAttributesTransfer;
use Generated\Shared\Transfer\RestCmsPagesTransfer;

class CmsPageMapper implements CmsPageMapperInterface
{
    /**
     * @uses \Spryker\Glue\CmsPagesRestApi\Processor\CmsPage\CmsPageReader::CMS_PAGES
     */
    protected const CMS_PAGES = 'cms_pages';

    /**
     * @phpstan-param array<string, mixed> $searchResult
     *
     * @param array $searchResult
     * @param \Generated\Shared\Transfer\RestCmsPagesTransfer $restCmsPagesAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCmsPagesTransfer
     */
    public function mapSearchResultToRestCmsPagesTransfer(
        array $searchResult,
        RestCmsPagesTransfer $restCmsPagesAttributesTransfer
    ): RestCmsPagesTransfer {
        $restCmsPagesAttributesTransfer = $restCmsPagesAttributesTransfer->fromArray($searchResult, true);

        return $this->mapDataToRestCmsPageTransfer(
            $restCmsPagesAttributesTransfer,
            $searchResult
        );
    }

    /**
     * @phpstan-param array<string, mixed> $searchResult
     *
     * @param \Generated\Shared\Transfer\RestCmsPagesTransfer $restCmsPagesAttributesTransfer
     * @param array $searchResult
     *
     * @return \Generated\Shared\Transfer\RestCmsPagesTransfer
     */
    protected function mapDataToRestCmsPageTransfer(
        RestCmsPagesTransfer $restCmsPagesAttributesTransfer,
        array $searchResult
    ): RestCmsPagesTransfer {
        if (!isset($searchResult[static::CMS_PAGES]) || !is_array($searchResult[static::CMS_PAGES])) {
            return $restCmsPagesAttributesTransfer;
        }

        foreach ($searchResult[static::CMS_PAGES] as $cmsPageStorageTransfer) {
            $restCmsPagesAttributesTransfer->addRestCmsPageAttributes(
                (new RestCmsPageAttributesTransfer())->fromArray($cmsPageStorageTransfer->toArray(), true)
            );
        }

        return $restCmsPagesAttributesTransfer;
    }
}
