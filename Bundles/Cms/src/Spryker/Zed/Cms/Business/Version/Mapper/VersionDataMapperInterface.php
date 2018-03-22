<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Version\Mapper;

use Generated\Shared\Transfer\CmsVersionDataTransfer;
use Orm\Zed\Cms\Persistence\SpyCmsPage;
use Orm\Zed\Cms\Persistence\SpyCmsVersion;

interface VersionDataMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsVersionDataTransfer $cmsVersionDataTransfer
     *
     * @return string
     */
    public function mapToJsonData(CmsVersionDataTransfer $cmsVersionDataTransfer);

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     *
     * @return \Generated\Shared\Transfer\CmsVersionDataTransfer
     */
    public function mapToCmsVersionDataTransfer(SpyCmsPage $cmsPageEntity);

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsVersion $cmsVersionEntity
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer
     */
    public function mapToCmsVersionTransfer(SpyCmsVersion $cmsVersionEntity);

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     *
     * @return \Generated\Shared\Transfer\CmsTemplateTransfer
     */
    public function mapToCmsTemplateData(SpyCmsPage $cmsPageEntity);

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     *
     * @return \Generated\Shared\Transfer\CmsPageTransfer
     */
    public function mapToCmsPageLocalizedAttributesData(SpyCmsPage $cmsPageEntity);

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsPage $cmsPageEntity
     *
     * @return \Generated\Shared\Transfer\CmsGlossaryTransfer
     */
    public function mapToCmsGlossaryKeyMappingsData(SpyCmsPage $cmsPageEntity);
}
