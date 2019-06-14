<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Page;

use Generated\Shared\Transfer\CmsPageAttributesTransfer;
use Generated\Shared\Transfer\CmsPageMetaAttributesTransfer;
use Generated\Shared\Transfer\CmsPageTransfer;
use Orm\Zed\Cms\Persistence\SpyCmsPage;
use Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributes;

interface CmsPageMapperInterface
{
    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     *
     * @return array
     */
    public function mapCmsPageUrlLocale(SpyCmsPage $cmsPageEntity): array;

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributes $cmsPageLocalizedAttributesEntity
     * @param string|null $url
     *
     * @return \Generated\Shared\Transfer\CmsPageAttributesTransfer
     */
    public function mapCmsLocalizedAttributesTransfer(SpyCmsPageLocalizedAttributes $cmsPageLocalizedAttributesEntity, ?string $url = null): CmsPageAttributesTransfer;

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPageLocalizedAttributes $cmsPageLocalizedAttributesEntity
     *
     * @return \Generated\Shared\Transfer\CmsPageMetaAttributesTransfer
     */
    public function mapCmsPageMetaAttributes(SpyCmsPageLocalizedAttributes $cmsPageLocalizedAttributesEntity): CmsPageMetaAttributesTransfer;

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     *
     * @return \Generated\Shared\Transfer\CmsPageTransfer
     */
    public function mapCmsPageTransfer(SpyCmsPage $cmsPageEntity): CmsPageTransfer;
}
