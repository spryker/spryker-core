<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\ContentGui\Business\ContentGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\ContentGui\Communication\ContentGuiCommunicationFactory getFactory()
 */
class CreateContentController extends AbstractController
{
    /**
     * @var string
     */
    protected const PARAM_TERM_KEY = 'term-key';

    /**
     * @var string
     */
    protected const PARAM_REDIRECT_URL = 'redirect-url';

    /**
     * @var string
     */
    protected const URL_REDIRECT_CONTENT_LIST_PAGE = '/content-gui/list-content';

    /**
     * @var string
     */
    protected const MESSAGE_SUCCESS_CONTENT_CREATE = 'Content item has been successfully created.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        /** @var string|null $termKey */
        $termKey = $request->query->get(static::PARAM_TERM_KEY);
        if (!$termKey) {
            return $this->redirectResponse(static::URL_REDIRECT_CONTENT_LIST_PAGE);
        }

        $dataProvider = $this->getFactory()->createContentFormDataProvider();
        $contentForm = $this->getFactory()
            ->getContentForm(
                $dataProvider->getData($termKey),
                $dataProvider->getOptions($termKey),
            )
            ->handleRequest($request);

        if ($contentForm->isSubmitted() && $contentForm->isValid()) {
            $data = $contentForm->getData();
            $this->getFactory()
                ->getContentFacade()
                ->create($data);

            $this->addSuccessMessage(static::MESSAGE_SUCCESS_CONTENT_CREATE);

            return $this->redirectResponse(
                (string)$request->query->get(
                    static::PARAM_REDIRECT_URL,
                    static::URL_REDIRECT_CONTENT_LIST_PAGE,
                ),
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
