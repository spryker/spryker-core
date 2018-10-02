<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockGui\Communication\Controller;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\CmsBlock\Business\Exception\CmsBlockTemplateNotFoundException;
use Spryker\Zed\CmsBlockGui\Communication\Form\Block\CmsBlockForm;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CmsBlockGui\Communication\CmsBlockGuiCommunicationFactory getFactory()
 */
class CreateBlockController extends AbstractController
{
    public const ERROR_MESSAGE_INVALID_DATA_PROVIDED = 'Invalid data provided.';
    public const ERROR_MESSAGE_LOST_TEMPLATE = 'Selected template doesn\'t exist anymore.';
    public const MESSAGE_SUCCESSFUL_CMS_BLOCK_CREATED = 'CMS Block was created successfully.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $this->getFactory()
            ->getCmsBlockFacade()
            ->syncTemplate($this->getFactory()->getConfig()->getTemplatePath());

        $availableLocales = $this->getFactory()
            ->getLocaleFacade()
            ->getLocaleCollection();

        $cmsBlockFormDataProvider = $this->getFactory()
            ->createCmsBlockFormDataProvider();

        $cmsBlockForm = $this->getFactory()
            ->getCmsBlockForm($cmsBlockFormDataProvider)
            ->handleRequest($request);

        if ($cmsBlockForm->isSubmitted()) {
            if ($cmsBlockForm->isValid()) {
                $cmsBlockTransfer = $this->createBlock($cmsBlockForm);

                if (!empty($cmsBlockTransfer)) {
                    $redirectUrl = $this->createSuccessRedirectUrl($cmsBlockTransfer);
                    return $this->redirectResponse($redirectUrl);
                }
            } else {
                $this->addErrorMessage(static::ERROR_MESSAGE_INVALID_DATA_PROVIDED);
            }
        }

        return $this->viewResponse([
            'cmsBlockForm' => $cmsBlockForm->createView(),
            'availableLocales' => $availableLocales,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $cmsBlockForm
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer|null
     */
    protected function createBlock(FormInterface $cmsBlockForm)
    {
        $cmsBlockTransfer = null;

        try {
            $cmsBlockTransfer = $this->getFactory()
                ->getCmsBlockFacade()
                ->createCmsBlock($cmsBlockForm->getData());

            $this->addSuccessMessage(static::MESSAGE_SUCCESSFUL_CMS_BLOCK_CREATED);
        } catch (CmsBlockTemplateNotFoundException $exception) {
            $this->addErrorMessage(static::ERROR_MESSAGE_INVALID_DATA_PROVIDED);

            $cmsBlockForm->get(CmsBlockForm::FIELD_FK_TEMPLATE)
                ->addError(new FormError(static::ERROR_MESSAGE_LOST_TEMPLATE));
        }

        return $cmsBlockTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return string
     */
    protected function createSuccessRedirectUrl(CmsBlockTransfer $cmsBlockTransfer)
    {
        return Url::generate(
            '/cms-block-gui/edit-glossary',
            [EditGlossaryController::URL_PARAM_ID_CMS_BLOCK => $cmsBlockTransfer->getIdCmsBlock()]
        )
            ->build();
    }
}
