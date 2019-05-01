<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ContentGui\Communication\ContentGuiCommunicationFactory getFactory()
 */
class EditContentController extends AbstractController
{
    protected const PARAM_ID_CONTENT = 'id-content';
    protected const PARAM_TERM_KEY = 'term-key';
    protected const PARAM_REDIRECT_URL = 'redirect-url';
    protected const URL_REDIRECT_CONTENT_LIST_PAGE = '/content-gui/list-content';
    protected const MESSAGE_SUCCESS_CONTENT_CREATE = 'Content item has been successfully updated.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $contentId = $request->query->get(static::PARAM_ID_CONTENT);
        $termKey = $request->query->get(static::PARAM_TERM_KEY, '');

        if (empty($termKey) || empty($contentId)) {
            return $this->redirectResponse(static::URL_REDIRECT_CONTENT_LIST_PAGE);
        }
        $dataProvider = $this->getFactory()->createContentFormDataProvider();
        $contentForm = $this->getFactory()
            ->getContentForm(
                $dataProvider->getData($termKey, $contentId),
                $dataProvider->getOptions($termKey, $contentId)
            )
            ->handleRequest($request);

        if ($contentForm->isSubmitted() && $contentForm->isValid()) {
            /** @var \Generated\Shared\Transfer\ContentTransfer $data */
            $data = $contentForm->getData();
            $this->getFactory()
                ->getContentFacade()
                ->update($data);

            $this->addSuccessMessage(static::MESSAGE_SUCCESS_CONTENT_CREATE);

            return $this->redirectResponse(
                $request->query->get(static::PARAM_REDIRECT_URL, static::URL_REDIRECT_CONTENT_LIST_PAGE)
            );
        }
        $contentTabs = $this->getFactory()->createContentTabs();

        return $this->viewResponse([
            'contentTabs' => $contentTabs->createView(),
            'contentForm' => $contentForm->createView(),
            'backButton' => static::URL_REDIRECT_CONTENT_LIST_PAGE,
            'contentName' => $this->getFactory()->createContentResolver()->getContentPlugin($termKey)->getTermKey(),
        ]);
    }
}
