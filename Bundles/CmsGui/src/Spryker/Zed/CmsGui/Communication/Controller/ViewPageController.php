<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Controller;

use Generated\Shared\Transfer\CmsPageTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CmsGui\Communication\CmsGuiCommunicationFactory getFactory()
 */
class ViewPageController extends AbstractController
{
    public const URL_PARAM_ID_CMS_PAGE = 'id-cms-page';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idCmsPage = $this->castId($request->query->get(static::URL_PARAM_ID_CMS_PAGE));

        $cmsVersionTransfer = $this->getFactory()
            ->getCmsFacade()
            ->findLatestCmsVersionByIdCmsPage($idCmsPage);

        $cmsLocalizedPageEntity = $this->getFactory()->getCmsQueryContainer()->queryCmsPageLocalizedAttributesByFkPage($idCmsPage)->findOne();

        if ($cmsVersionTransfer === null) {
            $this->addErrorMessage("Cms page with id %s doesn't exist", ['%s' => $idCmsPage]);

            return $this->redirectResponse($this->getFactory()->getConfig()->getDefaultRedirectUrl());
        }

        $cmsVersionDataTransfer = $this->getFactory()->getCmsFacade()->getCmsVersionData($idCmsPage);

        $cmsVersionDataHelper = $this->getFactory()->createCmsVersionDataHelper();
        $cmsVersionDataTransfer = $cmsVersionDataHelper->mapCmsPageTransferWithUrl($cmsVersionDataTransfer);

        $cmsPageTransfer = $cmsVersionDataTransfer->getCmsPage();

        $relatedStoreNames = $this->getStoreNames($cmsPageTransfer);

        return [
            'cmsPage' => $cmsPageTransfer,
            'cmsGlossary' => $cmsVersionDataTransfer->getCmsGlossary(),
            'cmsVersion' => $cmsVersionTransfer,
            'pageCreatedDate' => $cmsLocalizedPageEntity->getCreatedAt(),
            'relatedStoreNames' => $relatedStoreNames,
            'isPageTemplateWithPlaceholders' => $this->isPageTemplateWithPlaceholders($idCmsPage),
        ];
    }

    /**
     * @param int $idCmsPage
     *
     * @return bool
     */
    protected function isPageTemplateWithPlaceholders(int $idCmsPage): bool
    {
        $cmsGlossaryTransfer = $this->getFactory()->getCmsFacade()->findPageGlossaryAttributes($idCmsPage);

        if (!$cmsGlossaryTransfer) {
            return false;
        }

        return $cmsGlossaryTransfer->getGlossaryAttributes()->count() > 0;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     *
     * @return string[]
     */
    protected function getStoreNames(CmsPageTransfer $cmsPageTransfer): array
    {
        $storeRelation = $cmsPageTransfer->getStoreRelation();

        if (!$storeRelation) {
            return [];
        }

        $stores = $storeRelation->getStores();

        return array_map(function (StoreTransfer $storeTransfer) {
            return $storeTransfer->getName();
        }, $stores->getArrayCopy());
    }
}
