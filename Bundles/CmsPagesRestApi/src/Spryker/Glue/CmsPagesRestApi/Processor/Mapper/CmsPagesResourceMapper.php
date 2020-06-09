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
    protected const CMS_PAGES = 'cms_pages';

    /**
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

        foreach ($searchResult[static::CMS_PAGES] as $cmsPage) {
            $restCmsPagesAttributesTransfer->addRestCmsPage(
                (new RestCmsPageTransfer())->fromArray($cmsPage, true)
            );
        }

        return $restCmsPagesAttributesTransfer;
    }
}
