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
class CreateGlossaryController extends AbstractController
{

    const URL_PARAM_ID_CMS_PAGE = 'id-cms-page';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idCmsPage = $this->castId($request->get(static::URL_PARAM_ID_CMS_PAGE));

        $cmsGlossaryTransfer = $this->getFactory()
            ->getCmsFacade()
            ->getPageGlossaryAttributes($idCmsPage);

        $availableLocales = $this->getFactory()
            ->getLocaleFacade()
            ->getLocaleCollection();

        $cmsGlossaryFormDataProvider = $this->getFactory()
            ->createCmsGlossaryFormDataProvider($availableLocales, $cmsGlossaryTransfer);

        $placeholderTabs = $this->getFactory()
            ->createPlaceholderTabs($cmsGlossaryTransfer);

        $glossaryForm = $this->getFactory()
            ->createCmsGlossaryForm($cmsGlossaryFormDataProvider);
        $glossaryForm->handleRequest($request);

        if ($glossaryForm->isValid()) {
            $idCmsPage = $this->getFactory()
                ->getCmsFacade()
                ->saveCmsGlossary($glossaryForm->getData());
        }

        return [
            'glossaryForm' => $glossaryForm->createView(),
            'placeholderTabs' => $placeholderTabs->createView(),
            'availableLocales' => $availableLocales,
            'cmsGlossary' => $cmsGlossaryTransfer,
            'idCmsPage' => $idCmsPage,
        ];
    }

}
