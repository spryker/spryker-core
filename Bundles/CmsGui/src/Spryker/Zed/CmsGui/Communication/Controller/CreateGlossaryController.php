<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsGui\Communication\Controller;

use Generated\Shared\Transfer\CmsPageTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \Spryker\Zed\CmsGui\Communication\CmsGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsGui\CmsGuiConfig getConfig()
 */
class CreateGlossaryController extends AbstractController
{
    public const URL_PARAM_ID_CMS_PAGE = 'id-cms-page';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idCmsPage = $this->castId($request->get(static::URL_PARAM_ID_CMS_PAGE));

        $cmsGlossaryTransfer = $this->getFactory()
            ->getCmsFacade()
            ->findPageGlossaryAttributes($idCmsPage);

        if ($cmsGlossaryTransfer === null) {
            throw new NotFoundHttpException(
                sprintf('Cms page with id "%d" not found!', $idCmsPage)
            );
        }

        $cmsGlossaryFormDataProvider = $this->getFactory()
            ->createCmsGlossaryFormTypeDataProvider();

        $placeholderTabs = $this->getFactory()
            ->createPlaceholderTabs($cmsGlossaryTransfer);

        $glossaryForm = $this->getFactory()
            ->createCmsGlossaryForm($cmsGlossaryFormDataProvider, $idCmsPage)
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

        $availableLocales = $this->getFactory()
            ->getLocaleFacade()
            ->getLocaleCollection();

        $cmsVersion = $this->getFactory()
            ->getCmsFacade()
            ->findLatestCmsVersionByIdCmsPage($idCmsPage);

        $cmsPageTransfer = $this->getFactory()
            ->getCmsFacade()
            ->findCmsPageById($idCmsPage);

        return [
            'glossaryForm' => $glossaryForm->createView(),
            'placeholderTabs' => $placeholderTabs->createView(),
            'availableLocales' => $availableLocales,
            'idCmsPage' => $idCmsPage,
            'cmsVersion' => $cmsVersion,
            'cmsPage' => $cmsPageTransfer,
            'viewActionButtons' => $this->getViewActionButtons($cmsPageTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CmsPageTransfer $cmsPageTransfer
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer[]
     */
    protected function getViewActionButtons(CmsPageTransfer $cmsPageTransfer)
    {
        $viewActionButtons = [];
        foreach ($this->getFactory()->getCreateGlossaryExpanderPlugins() as $createGlossaryExpanderPlugin) {
            $viewActionButtons = array_merge($viewActionButtons, $createGlossaryExpanderPlugin->getViewActionButtons($cmsPageTransfer));
        }

        return $viewActionButtons;
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
