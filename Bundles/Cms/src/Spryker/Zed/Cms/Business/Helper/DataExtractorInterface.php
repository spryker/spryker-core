<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Helper;

use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Generated\Shared\Transfer\CmsPageDataTransfer;
use Generated\Shared\Transfer\CmsPageTransfer;

interface DataExtractorInterface
{

    /**
     * @param string $data
     *
     * @return \Generated\Shared\Transfer\CmsVersionDataTransfer
     */
    public function extractCmsVersionDataTransfer($data);

    /**
     * @param \Generated\Shared\Transfer\CmsPageDataTransfer $cmsPageDataTransfer
     * @param string $data
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\CmsPageDataTransfer
     */
    public function expandCmsPageDataTransfer(CmsPageDataTransfer $cmsPageDataTransfer, $data, $localeName);

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     * @param string $localeName
     *
     * @return array
     */
    public function extractPlaceholdersByLocale(CmsGlossaryTransfer $cmsGlossaryTransfer, $localeName);

    /**
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\CmsPageAttributesTransfer|null
     */
    public function extractPageAttributeByLocale(CmsPageTransfer $cmsPageTransfer, $localeName);

    /**
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\CmsPageMetaAttributesTransfer|null
     */
    public function extractMetaAttributeByLocales(CmsPageTransfer $cmsPageTransfer, $localeName);

}
