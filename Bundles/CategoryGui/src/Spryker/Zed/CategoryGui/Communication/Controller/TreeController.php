<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CategoryGui\Communication\CategoryGuiCommunicationFactory getFactory()
 */
class TreeController extends AbstractController
{
    protected const REQUEST_PARAM_ID_ROOT_NODE = 'id-root-node';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request): array
    {
        $categoryTree = $this->getCategoryTree($request);

        return $this->viewResponse([
            'categoryTree' => $categoryTree,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function getCategoryTree(Request $request): array
    {
        $idRootNode = $this->castId($request->query->get(static::REQUEST_PARAM_ID_ROOT_NODE));
        $localeTransfer = $this->getFactory()->getCurrentLocale();

        return $this
            ->getFactory()
            ->getCategoryFacade()
            ->getTreeNodeChildrenByIdCategoryAndLocale($idRootNode, $localeTransfer);
    }
}
