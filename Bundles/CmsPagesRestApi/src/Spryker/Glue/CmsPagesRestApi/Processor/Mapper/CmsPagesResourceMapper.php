<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CmsPagesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestCmsPagesAttributesTransfer;
use Generated\Shared\Transfer\RestCmsPageTransfer;

class CmsPagesResourceMapper implements CmsPagesResourceMapperInterface
{
    /**
     * @uses \Spryker\Glue\CmsPagesRestApi\Processor\CmsPage\CmsPageReader::CMS_PAGES
     */
    protected const CMS_PAGES = 'cms_pages';

    /**
     * @phpstan-param array<string, mixed> $searchResult
     *
     * @param array $searchResult
     * @param \Generated\Shared\Transfer\RestCmsPagesAttributesTransfer $restCmsPagesAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCmsPagesAttributesTransfer
     */
    public function mapSearchResultToRestAttributesTransfer(
        array $searchResult,
        RestCmsPagesAttributesTransfer $restCmsPagesAttributesTransfer
    ): RestCmsPagesAttributesTransfer {
        $restCmsPagesAttributesTransfer = $restCmsPagesAttributesTransfer->fromArray($searchResult, true);

        return $this->mapDataToRestCmsPageTransfer(
            $restCmsPagesAttributesTransfer,
            $searchResult
        );
    }

    /**
     * @phpstan-param array<string, mixed> $searchResult
     *
     * @param \Generated\Shared\Transfer\RestCmsPagesAttributesTransfer $restCmsPagesAttributesTransfer
     * @param array $searchResult
     *
     * @return \Generated\Shared\Transfer\RestCmsPagesAttributesTransfer
     */
    protected function mapDataToRestCmsPageTransfer(
        RestCmsPagesAttributesTransfer $restCmsPagesAttributesTransfer,
        array $searchResult
    ): RestCmsPagesAttributesTransfer {
        if (!isset($searchResult[static::CMS_PAGES]) || !is_array($searchResult[static::CMS_PAGES])) {
            return $restCmsPagesAttributesTransfer;
        }

        foreach ($searchResult[static::CMS_PAGES] as $cmsPageStorageTransfer) {
            $restCmsPagesAttributesTransfer->addRestCmsPage(
                (new RestCmsPageTransfer())->fromArray($cmsPageStorageTransfer->toArray(), true)
            );
        }

        return $restCmsPagesAttributesTransfer;
    }
}
