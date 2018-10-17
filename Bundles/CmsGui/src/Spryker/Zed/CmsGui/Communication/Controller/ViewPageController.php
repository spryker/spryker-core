<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \Spryker\Zed\CmsGui\Communication\CmsGuiCommunicationFactory getFactory()
 */
class ViewPageController extends AbstractController
{
    public const URL_PARAM_ID_CMS_PAGE = 'id-cms-page';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idCmsPage = $this->castId($request->query->get(static::URL_PARAM_ID_CMS_PAGE));

        $cmsVersionTransfer = $this->getFactory()
            ->getCmsFacade()
            ->findLatestCmsVersionByIdCmsPage($idCmsPage);

        $cmsLocalizedPageEntity = $this->getFactory()->getCmsQueryContainer()->queryCmsPageLocalizedAttributesByFkPage($idCmsPage)->findOne();

        if ($cmsVersionTransfer === null) {
            throw new NotFoundHttpException(
                sprintf('Cms published page with id "%d" not found.', $idCmsPage)
            );
        }

        $cmsVersionDataHelper = $this->getFactory()->createCmsVersionDataHelper();
        $cmsVersionDataTransfer = $cmsVersionDataHelper->mapToCmsVersionDataTransfer($cmsVersionTransfer);

        return [
            'cmsPage' => $cmsVersionDataTransfer->getCmsPage(),
            'cmsGlossary' => $cmsVersionDataTransfer->getCmsGlossary(),
            'cmsVersion' => $cmsVersionTransfer,
            'pageCreatedDate' => $cmsLocalizedPageEntity->getCreatedAt(),
        ];
    }
}
