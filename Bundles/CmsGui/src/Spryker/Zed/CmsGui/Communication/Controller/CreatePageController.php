<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace  Spryker\Zed\CmsGui\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CmsGui\Communication\CmsGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsGui\Business\CmsGuiFacade getFacade()
 */
class CreatePageController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $pageTabs = $this->getFactory()->createPageTabs();

        $availableLocales = $this->getFactory()->getLocaleFacade()->getLocaleCollection();

        $cmsPageFormTypeDataProvider = $this->getFactory()->createCmsPageFormTypeDatProvider($availableLocales);

        $pageForm = $this->getFactory()->createCmsPageForm($cmsPageFormTypeDataProvider);
        $pageForm->handleRequest($request);

        if ($pageForm->isValid()) {
            $cmsPageTransfer = $pageForm->getData();
        }

        return [
            'pageTabs' => $pageTabs->createView(),
            'pageForm' => $pageForm->createView(),
            'availableLocales' => $availableLocales,
        ];
    }
}
