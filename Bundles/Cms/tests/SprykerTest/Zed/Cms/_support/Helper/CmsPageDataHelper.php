<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Cms\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CmsPageAttributesBuilder;
use Generated\Shared\DataBuilder\CmsPageBuilder;
use Generated\Shared\Transfer\CmsPageTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Zed\Cms\Business\CmsFacadeInterface;
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
    public function haveCmsPage(array $seedData = []): CmsPageTransfer
    {
        $cmsPageTransfer = $this->generateLocalizedCmsPageTransfer($seedData);

        $this->setStoreRelation($cmsPageTransfer, $seedData);

        $idCmsPage = $this->getCmsFacade()->createPage($cmsPageTransfer);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($idCmsPage): void {
            $this->cleanupCmsPage($idCmsPage);
        });

        return $this->getCmsFacade()->findCmsPageById($idCmsPage);
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CmsPageTransfer
     */
    public function haveCmsPagePublished(array $seedData = []): CmsPageTransfer
    {
        $cmsPageTransfer = $this->haveCmsPage($seedData);

        $cmsGlossaryTransfer = $this->getCmsFacade()->findPageGlossaryAttributes($cmsPageTransfer->getFkPage());
        $this->getCmsFacade()->saveCmsGlossary($cmsGlossaryTransfer);

        $this->getCmsFacade()->publishWithVersion($cmsPageTransfer->getFkPage());

        return $cmsPageTransfer;
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CmsPageTransfer
     */
    protected function generateLocalizedCmsPageTransfer(array $seedData = []): CmsPageTransfer
    {
        $cmsPageTransfer = (new CmsPageBuilder($seedData))->build();

        $cmsPageLocalizedAttributes = (new CmsPageAttributesBuilder($seedData))->build();
        $cmsPageTransfer->addPageAttribute($cmsPageLocalizedAttributes);

        return $cmsPageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     * @param array $seedData
     *
     * @return void
     */
    protected function setStoreRelation(CmsPageTransfer $cmsPageTransfer, array $seedData = []): void
    {
        if (!isset($seedData[CmsPageTransfer::STORE_RELATION])) {
            return;
        }

        $cmsPageTransfer->setStoreRelation(
            (new StoreRelationTransfer())
                ->fromArray($seedData[CmsPageTransfer::STORE_RELATION]),
        );
    }

    /**
     * @return \Spryker\Zed\Cms\Business\CmsFacadeInterface
     */
    protected function getCmsFacade(): CmsFacadeInterface
    {
        return $this->getLocator()->cms()->facade();
    }

    /**
     * @param int $idCmsPage
     *
     * @return void
     */
    protected function cleanupCmsPage(int $idCmsPage): void
    {
        $this->getCmsFacade()->deletePageById($idCmsPage);
    }
}
