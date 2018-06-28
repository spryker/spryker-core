<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Cms\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CmsPageAttributesBuilder;
use Generated\Shared\DataBuilder\CmsPageBuilder;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class CmsPageDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CmsPageTransfer
     */
    public function haveCmsPage(array $seedData = [])
    {
        $cmsPageTransfer = $this->generateLocalizedCmsPageTransfer($seedData);

        $idCmsPage = $this->getCmsFacade()->createPage($cmsPageTransfer);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($idCmsPage) {
            $this->cleanupCmsPage($idCmsPage);
        });

        return $this->getCmsFacade()->findCmsPageById($idCmsPage);
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CmsPageTransfer
     */
    protected function generateLocalizedCmsPageTransfer(array $seedData = [])
    {
        $cmsPageTransfer = (new CmsPageBuilder($seedData))->build();

        $cmsPageLocalizedAttributes = (new CmsPageAttributesBuilder($seedData))->build();
        $cmsPageTransfer->addPageAttribute($cmsPageLocalizedAttributes);

        return $cmsPageTransfer;
    }

    /**
     * @return \Spryker\Zed\Cms\Business\CmsFacadeInterface
     */
    protected function getCmsFacade()
    {
        return $this->getLocator()->cms()->facade();
    }

    /**
     * @param int $idCmsPage
     *
     * @return void
     */
    protected function cleanupCmsPage($idCmsPage)
    {
        $this->getCmsFacade()->deletePageById($idCmsPage);
    }
}
