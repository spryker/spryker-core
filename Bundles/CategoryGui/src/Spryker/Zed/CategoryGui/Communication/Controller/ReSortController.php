<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfToken;

/**
 * @method \Spryker\Zed\CategoryGui\Persistence\CategoryGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\CategoryGui\Communication\CategoryGuiCommunicationFactory getFactory()
 */
class ReSortController extends CategoryAbstractController
{
    /**
     * @var string
     */
    protected const PARAM_RE_SORT_FORM_TOKEN_ID = 'category_nodes_re_sort_token';

    /**
     * @var string
     */
    protected const PARAM_REQUEST_RE_SORT_FORM_TOKEN = 'token';

    /**
     * @var string
     */
    protected const REQUEST_PARAM_ID_CATEGORY = 'id-category';

    /**
     * @var string
     */
    protected const REQUEST_PARAM_ID_NODE = 'id-node';

    /**
     * @var string
     */
    protected const REQUEST_PARAM_NODES = 'nodes';

    /**
     * @var string
     */
    protected const RESPONSE_KEY_CODE = 'code';

    /**
     * @var string
     */
    protected const RESPONSE_KEY_MESSAGE = 'message';

    /**
     * @var string
     */
    protected const RESPONSE_MESSAGE_TOKEN_IS_NOT_VALID = 'CSRF token is not valid.';

    /**
     * @var string
     */
    protected const RESPONSE_MESSAGE_RESORT_FAILED = 'Category nodes cannot be relocated.';

    /**
     * @var string
     */
    protected const RESPONSE_MESSAGE_RESORT_SUCCESS = 'Category nodes successfully re-sorted.';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<mixed>
     */
    public function indexAction(Request $request): array
    {
        $idCategoryNode = $request->get(static::REQUEST_PARAM_ID_NODE);
        $idLocale = $this->getCurrentLocale()->getIdLocaleOrFail();

        return [
            'items' => $this->getRepository()->getChildrenCategoryNodeNames($idCategoryNode, $idLocale),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function saveAction(Request $request): JsonResponse
    {
        $translationFacade = $this->getFactory()->getTranslatorFacade();

        if (!$this->isCsrfTokenValid($request->get(static::PARAM_REQUEST_RE_SORT_FORM_TOKEN))) {
            return $this->jsonResponse([
                static::RESPONSE_KEY_CODE => Response::HTTP_BAD_REQUEST,
                static::RESPONSE_KEY_MESSAGE => $translationFacade->trans(static::RESPONSE_MESSAGE_TOKEN_IS_NOT_VALID),
            ]);
        }

        $categoryNodesData = (string)$request->request->get(static::REQUEST_PARAM_NODES);
        $isUpdateSuccessful = $this->getFactory()
            ->createCategoryNodeOrderUpdater()
            ->updateCategoryNodeOrder($categoryNodesData);

        if (!$isUpdateSuccessful) {
            return $this->jsonResponse([
                static::RESPONSE_KEY_CODE => Response::HTTP_BAD_REQUEST,
                static::RESPONSE_KEY_MESSAGE => $translationFacade->trans(static::RESPONSE_MESSAGE_RESORT_FAILED),
            ]);
        }

        return $this->jsonResponse([
            static::RESPONSE_KEY_CODE => Response::HTTP_OK,
            static::RESPONSE_KEY_MESSAGE => $translationFacade->trans(static::RESPONSE_MESSAGE_RESORT_SUCCESS),
        ]);
    }

    /**
     * @param string|null $token
     *
     * @return bool
     */
    protected function isCsrfTokenValid(?string $token): bool
    {
        if (!$token) {
            return false;
        }

        $csrfToken = new CsrfToken(static::PARAM_RE_SORT_FORM_TOKEN_ID, $token);

        return $this->getFactory()->getCsrfTokenManager()->isTokenValid($csrfToken);
    }
}
