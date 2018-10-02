<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Category\Communication\CategoryCommunicationFactory getFactory()
 * @method \Spryker\Zed\Category\Business\CategoryFacadeInterface getFacade()
 */
class TreeController extends AbstractController
{
    public const PARAM_ID_ROOT_NODE = 'id-root-node';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
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
    protected function getCategoryTree(Request $request)
    {
        $idRootNode = $this->castId($request->query->get(self::PARAM_ID_ROOT_NODE));
        $localeTransfer = $this->getFactory()->getCurrentLocale();

        return $this
            ->getFacade()
            ->getTreeNodeChildrenByIdCategoryAndLocale($idRootNode, $localeTransfer);
    }
}
