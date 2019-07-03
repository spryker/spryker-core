<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \Spryker\Zed\ContentGui\Business\ContentGuiFacade getFacade()
 * @method \Spryker\Zed\ContentGui\Communication\ContentGuiCommunicationFactory getFactory()
 */
class EditContentController extends AbstractController
{
    protected const PARAM_ID_CONTENT = 'id-content';
    protected const PARAM_TERM_KEY = 'term-key';
    protected const PARAM_REDIRECT_URL = 'redirect-url';
    protected const URL_REDIRECT_CONTENT_LIST_PAGE = '/content-gui/list-content';
    protected const MESSAGE_SUCCESS_CONTENT_UPDATE = 'Content item has been successfully updated.';
    protected const MESSAGE_ERROR_CONTENT_EDIT = 'Content item not found for id %d.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idContent = $this->castId($request->query->get(static::PARAM_ID_CONTENT));
        $dataProvider = $this->getFactory()->createContentFormDataProvider();
        $contentTransfer = $dataProvider->getData('', $idContent);

        if (!$contentTransfer) {
            throw new NotFoundHttpException(sprintf(static::MESSAGE_ERROR_CONTENT_EDIT, $idContent));
        }

        $contentForm = $this->getFactory()
            ->getContentForm(
                $contentTransfer,
                $dataProvider->getOptions('', $contentTransfer)
            )
            ->handleRequest($request);

        /** @var \Generated\Shared\Transfer\ContentTransfer $data */
        $contentFormData = $contentForm->getData();

        if ($contentForm->isSubmitted() && $contentForm->isValid()) {
            $this->getFactory()
                ->getContentFacade()
                ->update($contentFormData);

            $this->addSuccessMessage(static::MESSAGE_SUCCESS_CONTENT_UPDATE);

            return $this->redirectResponse(
                $request->query->get(static::PARAM_REDIRECT_URL, static::URL_REDIRECT_CONTENT_LIST_PAGE)
            );
        }
        $contentTabs = $this->getFactory()->createContentTabs();

        return $this->viewResponse([
            'contentTabs' => $contentTabs->createView(),
            'contentForm' => $contentForm->createView(),
            'backButton' => static::URL_REDIRECT_CONTENT_LIST_PAGE,
            'contentKey' => $contentFormData->getKey(),
            'contentName' => $contentTransfer->getContentTermKey(),
        ]);
    }
}
