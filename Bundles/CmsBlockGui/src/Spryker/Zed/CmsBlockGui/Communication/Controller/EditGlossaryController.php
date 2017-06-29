<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockGui\Communication\Controller;

use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \Spryker\Zed\CmsBlockGui\Communication\CmsBlockGuiCommunicationFactory getFactory()
 */
class EditGlossaryController extends AbstractController
{

    const URL_PARAM_ID_CMS_BLOCK = 'id-cms-block';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idCmsBlock = $this->castId($request->get(static::URL_PARAM_ID_CMS_BLOCK));

        $glossaryTransfer = $this->getFactory()
            ->getCmsBlockFacade()
            ->findGlossary($idCmsBlock);

        if ($glossaryTransfer === null) {
            throw new NotFoundHttpException(
                sprintf('Cms block with id "%d" not found!', $idCmsBlock)
            );
        }

        $placeholderTabs = $this->getFactory()
            ->createCmsBlockPlaceholderTabs($glossaryTransfer);

        $glossaryFormDataProvider = $this->getFactory()
            ->createCmsBlockGlossaryFormDataProvider();

        $glossaryForm = $this->getFactory()
            ->createCmsBlockGlossaryForm($glossaryFormDataProvider, $idCmsBlock)
            ->handleRequest($request);

        if ($glossaryForm->isSubmitted()) {
            if ($glossaryForm->isValid()) {
                $this->getFactory()
                    ->getCmsBlockFacade()
                    ->saveGlossary($glossaryForm->getData());

                $this->addSuccessMessage('Placeholder translations successfully updated.');

                $redirectUrl = Url::generate(
                    '/cms-block-gui/edit-glossary/index',
                    [static::URL_PARAM_ID_CMS_BLOCK => $idCmsBlock]
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
            ->findCmsBlockById($idCmsBlock);

        return $this->viewResponse([
            'placeholderTabs' => $placeholderTabs->createView(),
            'glossaryForm' => $glossaryForm->createView(),
            'availableLocales' => $availableLocales,
            'cmsBlock' => $cmsBlockTransfer,
            'idCmsBlock' => $idCmsBlock,
        ]);
    }

}
