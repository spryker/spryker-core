<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsCollector\Business\Extractor;

use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Generated\Shared\Transfer\CmsPageTransfer;
use Generated\Shared\Transfer\CmsVersionDataTransfer;

interface DataExtractorInterface
{

    /**
     * @param string $data
     *
     * @return CmsVersionDataTransfer
     */
    public function extractCmsVersionDataTransfer($data);

    /**
     * @param \Generated\Shared\Transfer\CmsGlossaryTransfer $cmsGlossaryTransfer
     * @param $localeName
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
