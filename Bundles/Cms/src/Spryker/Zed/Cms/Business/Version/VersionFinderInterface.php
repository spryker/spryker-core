<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Version;

use Generated\Shared\Transfer\CmsVersionTransfer;

interface VersionFinderInterface
{

    /**
     * @param int $idCmsPage
     *
     * @return CmsVersionTransfer
     */
    public function findLatestCmsVersionByIdCmsPage($idCmsPage);

    /**
     * @param int $idCmsPage
     *
     * @return CmsVersionTransfer[]
     */
    public function findAllCmsVersionByIdCmsPage($idCmsPage);

    /**
     * @param int $idCmsPage
     * @param int $version
     *
     * @return CmsVersionTransfer
     */
    public function findCmsVersionByIdCmsPageAndVersion($idCmsPage, $version);

}
