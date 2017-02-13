<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Controller;

use Spryker\Shared\Url\Url;
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
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
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
            ->createCmsGlossaryFormTypeDataProvider($cmsGlossaryTransfer);

        $placeholderTabs = $this->getFactory()
            ->createPlaceholderTabs($cmsGlossaryTransfer);

        $glossaryForm = $this->getFactory()
            ->createCmsGlossaryForm($cmsGlossaryFormDataProvider)
            ->handleRequest($request);

        if ($glossaryForm->isSubmitted()) {
            if ($glossaryForm->isValid()) {
                $this->getFactory()
                    ->getCmsFacade()
                    ->saveCmsGlossary($glossaryForm->getData());

                $this->addSuccessMessage('Placeholder translations successfully updated.');

                $redirectUrl = Url::generate(
                    '/cms-gui/create-glossary/index',
                    [static::URL_PARAM_ID_CMS_PAGE => $idCmsPage]
                )->build();

                return $this->redirectResponse($redirectUrl);

            } else {
                $this->addErrorMessage('Invalid data provided.');
            }
        }

        return [
            'glossaryForm' => $glossaryForm->createView(),
            'placeholderTabs' => $placeholderTabs->createView(),
            'availableLocales' => $availableLocales,
            'cmsGlossary' => $cmsGlossaryTransfer,
            'idCmsPage' => $idCmsPage,
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function searchAction(Request $request)
    {
        $value = $request->query->get('value');
        $key = $request->query->get('key');

        $result = [];
        if ($key !== null) {
            $result = $this->getFactory()
                ->createAutocompleteDataProvider()
                ->getAutocompleteDataForTranslationKey($key);

        } elseif ($value != null) {
            $result = $this->getFactory()
                ->createAutocompleteDataProvider()
                ->getAutocompleteDataForTranslationValue($value);

        }

        return $this->jsonResponse($result);
    }

}
