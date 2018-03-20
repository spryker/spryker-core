<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockGui\Communication\Controller;

use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\CmsBlock\Business\Exception\CmsBlockTemplateNotFoundException;
use Spryker\Zed\CmsBlockGui\Communication\Form\Block\CmsBlockForm;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CmsBlockGui\Communication\CmsBlockGuiCommunicationFactory getFactory()
 */
class EditBlockController extends AbstractCmsBlockController
{
    const URL_PARAM_REDIRECT_URL = 'redirect-url';

    const MESSAGE_CMS_BLOCK_UPDATE_ERROR = 'Invalid data provided.';
    const MESSAGE_CMS_BLOCK_UPDATE_SUCCESS = 'CMS Block was updated successfully.';
    const MESSAGE_CMS_BLOCK_ACTIVATE_SUCCESS = 'CMS Block was activated successfully.';
    const MESSAGE_CMS_BLOCK_DEACTIVATE_SUCCESS = 'CMS Block was deactivated successfully.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $this->getFactory()
            ->getCmsBlockFacade()
            ->syncTemplate($this->getFactory()->getConfig()->getTemplatePath());

        $cmsBlockTransfer = $this->findCmsBlockById($request);

        if (!$cmsBlockTransfer) {
            $this->addErrorMessage(static::MESSAGE_CMS_BLOCK_INVALID_ID_ERROR);

            return $this->getNotFoundBlockRedirect();
        }

        $cmsBlockFormTypeDataProvider = $this->getFactory()
            ->createCmsBlockFormDataProvider();

        $cmsBlockForm = $this->getFactory()
            ->getCmsBlockForm($cmsBlockFormTypeDataProvider, $cmsBlockTransfer->getIdCmsBlock())
            ->handleRequest($request);

        if ($cmsBlockForm->isSubmitted()) {
            $isUpdated = $this->updateCmsBlock($cmsBlockForm);

            if ($isUpdated) {
                $redirectUrl = $this->createEditCmsBlockUrl($cmsBlockTransfer->getIdCmsBlock());
                return $this->redirectResponse($redirectUrl);
            }
        }

        $availableLocales = $this->getFactory()
            ->getLocaleFacade()
            ->getLocaleCollection();

        return $this->viewResponse([
            'idCmsBlock' => $cmsBlockTransfer->getIdCmsBlock(),
            'cmsBlockForm' => $cmsBlockForm->createView(),
            'availableLocales' => $availableLocales,
            'cmsBlock' => $cmsBlockTransfer,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function activateAction(Request $request)
    {
        $idCmsBlock = $this->castId($request->query->get(static::URL_PARAM_ID_CMS_BLOCK));
        $redirectUrl = $request->query->get(static::URL_PARAM_REDIRECT_URL, static::REDIRECT_URL_DEFAULT);

        $this->getFactory()
            ->getCmsBlockFacade()
            ->activateById($idCmsBlock);

        $this->addSuccessMessage(static::MESSAGE_CMS_BLOCK_ACTIVATE_SUCCESS);

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deactivateAction(Request $request)
    {
        $idCmsBlock = $this->castId($request->query->get(static::URL_PARAM_ID_CMS_BLOCK));
        $redirectUrl = $request->query->get(static::URL_PARAM_REDIRECT_URL, static::REDIRECT_URL_DEFAULT);

        $this->getFactory()
            ->getCmsBlockFacade()
            ->deactivateById($idCmsBlock);

        $this->addSuccessMessage(static::MESSAGE_CMS_BLOCK_DEACTIVATE_SUCCESS);

        return $this->redirectResponse($redirectUrl);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $cmsBlockForm
     *
     * @return bool
     */
    protected function updateCmsBlock(FormInterface $cmsBlockForm)
    {
        if ($cmsBlockForm->isSubmitted() && $cmsBlockForm->isValid()) {
            try {
                $this->getFactory()
                    ->getCmsBlockFacade()
                    ->updateCmsBlock($cmsBlockForm->getData());

                $this->addSuccessMessage(static::MESSAGE_CMS_BLOCK_UPDATE_SUCCESS);

                return true;
            } catch (CmsBlockTemplateNotFoundException $exception) {
                $cmsBlockForm
                    ->get(CmsBlockForm::FIELD_FK_TEMPLATE)
                    ->addError(new FormError('Selected template doesn\'t exist anymore'));
            }
        } else {
            $this->addErrorMessage(static::MESSAGE_CMS_BLOCK_UPDATE_ERROR);
        }

        return false;
    }

    /**
     * @param int $idCmsBlock
     *
     * @return string
     */
    protected function createEditCmsBlockUrl($idCmsBlock)
    {
        return Url::generate(
            '/cms-block-gui/edit-block',
            [static::URL_PARAM_ID_CMS_BLOCK => $idCmsBlock]
        )->build();
    }
}
