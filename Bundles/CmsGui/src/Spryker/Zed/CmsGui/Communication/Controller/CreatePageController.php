<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace  Spryker\Zed\CmsGui\Communication\Controller;

use Spryker\Shared\Url\Url;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\CmsGui\CmsGuiConfig;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CmsGui\Communication\CmsGuiCommunicationFactory getFactory()
 */
class CreatePageController extends AbstractController
{

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $this->getFactory()
            ->getCmsFacade()
            ->syncTemplate(CmsGuiConfig::CMS_FOLDER_PATH);

        $pageTabs = $this->getFactory()->createPageTabs();

        $availableLocales = $this->getFactory()
            ->getLocaleFacade()
            ->getLocaleCollection();

        $cmsPageFormTypeDataProvider = $this->getFactory()
            ->createCmsPageFormTypeDataProvider($availableLocales);

        $pageForm = $this->getFactory()
            ->createCmsPageForm($cmsPageFormTypeDataProvider)
            ->handleRequest($request);

        if ($pageForm->isSubmitted()) {
            if ($pageForm->isValid()) {
                $idCmsPage = $this->getFactory()
                    ->getCmsFacade()
                    ->createPage($pageForm->getData());

                $redirectUrl = Url::generate(
                    '/cms-gui/create-glossary/index',
                    [CreateGlossaryController::URL_PARAM_ID_CMS_PAGE => $idCmsPage]
                )->build();

                $this->addSuccessMessage('Page successfully created.');

                return $this->redirectResponse($redirectUrl);
            } else {
                $this->addErrorMessage('Invalid data provided.');
            }
        }

        return [
            'pageTabs' => $pageTabs->createView(),
            'pageForm' => $pageForm->createView(),
            'availableLocales' => $availableLocales,
        ];
    }

}
