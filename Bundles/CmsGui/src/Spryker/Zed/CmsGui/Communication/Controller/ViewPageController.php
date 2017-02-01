<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CmsGui\Communication\CmsGuiCommunicationFactory getFactory()
 */
class ViewPageController extends AbstractController
{

    const URL_PARAM_ID_CMS_PAGE = 'id-cms-page';

    /**
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idCmsPage = $this->castId($request->query->get(static::URL_PARAM_ID_CMS_PAGE));

        $cmsPageTransfer = $this->getFactory()
            ->getCmsFacade()
            ->getCmsPageById($idCmsPage);

        $cmsGlossaryTransfer = $this->getFactory()
            ->getCmsFacade()
            ->getPageGlossaryAttributes($idCmsPage);

        return [
            'cmsPage' => $cmsPageTransfer,
            'cmsGlossary' => $cmsGlossaryTransfer,
        ];
    }


}
