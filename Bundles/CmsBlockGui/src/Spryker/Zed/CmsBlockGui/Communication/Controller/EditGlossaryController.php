<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockGui\Communication\Controller;

use Spryker\Service\UtilText\Model\Url\Url;
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
        $cmsBlockTransfer = $this->findCmsBlockById($request);

        if ($cmsBlockTransfer === null) {
            $this->addErrorMessage(static::MESSAGE_CMS_BLOCK_INVALID_ID_ERROR);
            $redirectUrl = Url::generate('/cms-block-gui/list-block')->build();

            return $this->redirectResponse($redirectUrl);
        }

        $glossaryTransfer = $this->getFactory()
            ->getCmsBlockFacade()
            ->findGlossary($cmsBlockTransfer->getIdCmsBlock());

        $placeholderTabs = $this->getFactory()
            ->createCmsBlockPlaceholderTabs($glossaryTransfer);

        $glossaryFormDataProvider = $this->getFactory()
            ->createCmsBlockGlossaryFormDataProvider();

        $glossaryForm = $this->getFactory()
            ->getCmsBlockGlossaryForm($glossaryFormDataProvider, $cmsBlockTransfer->getIdCmsBlock())
            ->handleRequest($request);

        if ($glossaryForm->isSubmitted()) {
            if ($glossaryForm->isValid()) {
                $this->getFactory()
                    ->getCmsBlockFacade()
                    ->saveGlossary($glossaryForm->getData());

                $this->addSuccessMessage('Placeholder translations successfully updated.');

                $redirectUrl = Url::generate(
                    '/cms-block-gui/edit-glossary/index',
                    [static::URL_PARAM_ID_CMS_BLOCK => $cmsBlockTransfer->getIdCmsBlock()]
                )->build();

                return $this->redirectResponse($redirectUrl);
            } else {
                $this->addErrorMessage('Invalid data provided.');
            }
        }

        $availableLocales = $this->getFactory()
            ->getLocaleFacade()
            ->getLocaleCollection();

        $cmsBlockTransfer = $this->getFactory()
            ->getCmsBlockFacade()
            ->findCmsBlockById($cmsBlockTransfer->getIdCmsBlock());

        return $this->viewResponse([
            'placeholderTabs' => $placeholderTabs->createView(),
            'glossaryForm' => $glossaryForm->createView(),
            'availableLocales' => $availableLocales,
            'cmsBlock' => $cmsBlockTransfer,
            'idCmsBlock' => $cmsBlockTransfer->getIdCmsBlock(),
        ]);
    }
}
