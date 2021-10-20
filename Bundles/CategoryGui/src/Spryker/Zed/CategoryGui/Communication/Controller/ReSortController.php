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
        if (!$this->isCsrfTokenValid($request->get(static::PARAM_REQUEST_RE_SORT_FORM_TOKEN))) {
            return $this->jsonResponse([
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => 'CSRF token is not valid.',
            ]);
        }

        $categoryNodesData = (string)$request->request->get(static::REQUEST_PARAM_NODES);
        $this->getFactory()
            ->createCategoryNodeOrderUpdater()
            ->updateCategoryNodeOrder($categoryNodesData);

        return $this->jsonResponse([
            'code' => Response::HTTP_OK,
            'message' => 'Category nodes successfully re-sorted.',
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
