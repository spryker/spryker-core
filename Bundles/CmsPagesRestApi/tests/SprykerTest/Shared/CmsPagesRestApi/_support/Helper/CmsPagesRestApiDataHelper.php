<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\CmsPagesRestApi\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\CmsPageStorageTransfer;
use Orm\Zed\Cms\Persistence\Map\SpyCmsPageTableMap;
use Orm\Zed\Cms\Persistence\SpyCmsPageQuery;

class CmsPagesRestApiDataHelper extends Module
{
    /**
     * @param int $idCmsPage
     *
     * @return \Generated\Shared\Transfer\CmsPageStorageTransfer
     */
    public function getCmsPageUuidByIdCmsPage(int $idCmsPage): CmsPageStorageTransfer
    {
        $cmsPageUuid = SpyCmsPageQuery::create()
            ->filterByIdCmsPage($idCmsPage)
            ->select([SpyCmsPageTableMap::COL_UUID])
            ->findOne();

        return (new CmsPageStorageTransfer())
            ->setIdCmsPage($idCmsPage)
            ->setUuid($cmsPageUuid);
    }
}
