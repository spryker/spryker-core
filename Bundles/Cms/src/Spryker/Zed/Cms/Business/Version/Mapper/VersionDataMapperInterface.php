<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Version\Mapper;

use Generated\Shared\Transfer\CmsGlossaryTransfer;
use Generated\Shared\Transfer\CmsPageTransfer;
use Generated\Shared\Transfer\CmsTemplateTransfer;
use Generated\Shared\Transfer\CmsVersionDataTransfer;
use Orm\Zed\Cms\Persistence\SpyCmsPage;
use Orm\Zed\Cms\Persistence\SpyCmsVersion;

interface VersionDataMapperInterface
{

    /**
     * @param CmsVersionDataTransfer $cmsVersionDataTransfer
     *
     * @return string
     */
    public function mapToJsonData(CmsVersionDataTransfer $cmsVersionDataTransfer);

    /**
     * @param SpyCmsPage $cmsPageEntity
     *
     * @return CmsVersionDataTransfer
     */
    public function mapToCmsVersionDataTransfer(SpyCmsPage $cmsPageEntity);

    /**
     * @param \Orm\Zed\Cms\Persistence\SpyCmsVersion $cmsVersionEntity
     *
     * @return \Generated\Shared\Transfer\CmsVersionTransfer
     */
    public function mapToCmsVersionTransfer(SpyCmsVersion $cmsVersionEntity);

    /**
     * @param SpyCmsPage $cmsPageEntity
     *
     * @return CmsTemplateTransfer
     */
    public function mapToCmsTemplateData(SpyCmsPage $cmsPageEntity);

    /**
     * @param SpyCmsPage $cmsPageEntity
     *
     * @return CmsPageTransfer
     */
    public function mapToCmsPageLocalizedAttributesData(SpyCmsPage $cmsPageEntity);

    /**
     * @param SpyCmsPage $cmsPageEntity
     *
     * @return CmsGlossaryTransfer
     */
    public function mapToCmsGlossaryKeyMappingsData(SpyCmsPage $cmsPageEntity);
}
