<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ContentGui\Communication\ContentGuiCommunicationFactory getFactory()
 */
class CreateContentController extends AbstractController
{
    protected const PARAM_REDIRECT_URL = 'redirect-url';
    protected const URL_REDIRECT_CONTENT_PAGE = '/content-gui/list-content';
    protected const MESSAGE_ERROR_CONTENT_CREATE = 'Content item create failed.';
    protected const MESSAGE_SUCCESS_CONTENT_CREATE = 'Content item has been successfully created.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $dataProvider = $this->getFactory()->createContentFormDataProvider();
        $contentForm = $this->getFactory()
            ->getContentForm(
                $dataProvider->getData(),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($contentForm->isSubmitted() && $contentForm->isValid()) {
            if ($this->createContent($contentForm)) {
                return $this->redirectResponse(
                    $request->query->get(static::PARAM_REDIRECT_URL, static::URL_REDIRECT_CONTENT_PAGE)
                );
            }
        }
        $contentTabs = $this->getFactory()->createContentTabs();

        return $this->viewResponse([
            'contentTabs' => $contentTabs->createView(),
            'contentForm' => $contentForm->createView(),
            'backButton' => static::URL_REDIRECT_CONTENT_PAGE,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $contentForm
     *
     * @return bool
     */
    protected function createContent(FormInterface $contentForm): bool
    {
        /** @var \Generated\Shared\Transfer\ContentTransfer $contentTransfer */
        $contentTransfer = $contentForm->getData();
        $contentTransfer = $this->getFactory()
            ->getContentFacade()
            ->create($contentTransfer);

        if (!$contentTransfer->getIdContent()) {
            $this->addErrorMessage(static::MESSAGE_ERROR_CONTENT_CREATE);

            return false;
        }

        $this->addSuccessMessage(static::MESSAGE_SUCCESS_CONTENT_CREATE);

        return true;
    }
}
