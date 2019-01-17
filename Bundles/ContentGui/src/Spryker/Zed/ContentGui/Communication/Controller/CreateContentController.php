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
class CreateContentController extends AbstractController
{
    protected const PARAM_TERM_KEY = 'term-key';
    protected const PARAM_REDIRECT_URL = 'redirect-url';
    protected const URL_REDIRECT_CONTENT_PAGE = '/content-gui/edit-content?term-key=%s&id-content=%s';
    protected const MESSAGE_SUCCESS_CONTENT_CREATE = 'Content item has been successfully created.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $termKey = $request->query->get(static::PARAM_TERM_KEY, '');
        $dataProvider = $this->getFactory()->createContentFormDataProvider();
        $contentForm = $this->getFactory()
            ->getContentForm(
                $dataProvider->getData($termKey),
                $dataProvider->getOptions($termKey)
            )
            ->handleRequest($request);

        if ($contentForm->isSubmitted() && $contentForm->isValid()) {
            $data = $contentForm->getData();
            $contentTransfer = $this->getFactory()
                ->getContentFacade()
                ->create($data);

            $this->addSuccessMessage(static::MESSAGE_SUCCESS_CONTENT_CREATE);

            return $this->redirectResponse(
                $request->query->get(
                    static::PARAM_REDIRECT_URL,
                    sprintf(
                        static::URL_REDIRECT_CONTENT_PAGE,
                        $contentTransfer->getContentTermCandidateKey(),
                        $contentTransfer->getIdContent()
                    )
                )
            );
        }
        $contentTabs = $this->getFactory()->createContentTabs();

        return $this->viewResponse([
            'contentTabs' => $contentTabs->createView(),
            'contentForm' => $contentForm->createView(),
            'backButton' => static::URL_REDIRECT_CONTENT_PAGE,
            'contentItemName' => $this->getFactory()->createContentResolver()->getContentItemPlugin($termKey)->getTermKey(),
        ]);
    }
}
