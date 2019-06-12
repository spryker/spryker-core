<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockGui\Communication\Controller;

use Generated\Shared\Transfer\CmsBlockGlossaryTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CmsBlockGui\Communication\CmsBlockGuiCommunicationFactory getFactory()
 */
class EditGlossaryController extends AbstractCmsBlockController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idCmsBlock = $request->query->get(static::URL_PARAM_ID_CMS_BLOCK);
        $cmsBlockTransfer = $this->findCmsBlockById($idCmsBlock);

        if (!$cmsBlockTransfer) {
            $this->addErrorMessage(static::MESSAGE_CMS_BLOCK_INVALID_ID_ERROR);
            $redirectUrl = Url::generate('/cms-block-gui/list-block')->build();

            return $this->redirectResponse($redirectUrl);
        }

        $idCmsBlock = $cmsBlockTransfer->getIdCmsBlock();
        $glossaryForm = $this->getGlossaryForm($idCmsBlock, $request);

        if ($glossaryForm->isSubmitted()) {
            if ($glossaryForm->isValid()) {
                return $this->saveFormData($glossaryForm->getData(), $idCmsBlock);
            }

            $this->addErrorMessage('Invalid data provided.');
        }

        return $this->viewResponse([
            'placeholderTabs' => $this->getPlaceholderTabs($idCmsBlock),
            'glossaryForm' => $glossaryForm->createView(),
            'availableLocales' => $this->getFactory()->getLocaleFacade()->getLocaleCollection(),
            'cmsBlock' => $cmsBlockTransfer,
            'idCmsBlock' => $idCmsBlock,
        ]);
    }

    /**
     * @param int $idCmsBlock
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function getGlossaryForm(int $idCmsBlock, Request $request): FormInterface
    {
        $glossaryFormDataProvider = $this->getFactory()
            ->createCmsBlockGlossaryFormDataProvider();

        return $this->getFactory()
            ->getCmsBlockGlossaryForm($glossaryFormDataProvider, $idCmsBlock)
            ->handleRequest($request);
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer
     * @param int $idCmsBlock
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function saveFormData(CmsBlockGlossaryTransfer $cmsBlockGlossaryTransfer, int $idCmsBlock): RedirectResponse
    {
        $cmsBlockGlossaryTransfer = $this->getFactory()
            ->createCmsBlockGlossaryUpdater()
            ->updateBeforeSave($cmsBlockGlossaryTransfer);

        $this->getFactory()
            ->getCmsBlockFacade()
            ->saveGlossary($cmsBlockGlossaryTransfer);

        $this->addSuccessMessage('Placeholder translations successfully updated.');

        $redirectUrl = Url::generate(
            '/cms-block-gui/edit-glossary/index',
            [static::URL_PARAM_ID_CMS_BLOCK => $idCmsBlock]
        )->build();

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @param int $idCmsBlock
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    protected function getPlaceholderTabs(int $idCmsBlock): TabsViewTransfer
    {
        $cmsBlockGlossaryTransfer = $this->getFactory()
            ->getCmsBlockFacade()
            ->findGlossary($idCmsBlock);

        return $this->getFactory()->createCmsBlockPlaceholderTabs($cmsBlockGlossaryTransfer)->createView();
    }
}
