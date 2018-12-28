<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Category\Business\CategoryFacadeInterface getFacade()
 * @method \Spryker\Zed\Category\Communication\CategoryCommunicationFactory getFactory()
 * @method \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface getRepository()
 */
class ViewController extends AbstractController
{
    public const QUERY_PARAM_ID_CATEGORY = 'id-category';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $idCategory = $request->query->getInt(static::QUERY_PARAM_ID_CATEGORY);

        $categoryTransfer = $this->getFacade()->findCategoryById($idCategory);

        if ($categoryTransfer === null) {
            $this->addErrorMessage("Category with id %s doesn't exist", ['%s' => $idCategory]);

            return $this->redirectResponse($this->getFactory()->getConfig()->getDefaultRedirectUrl());
        }

        $localeTransfer = $this->getFactory()->getCurrentLocale();
        $readPlugins = $this->getFactory()
            ->getRelationReadPluginStack();

        $renderedRelations = [];
        foreach ($readPlugins as $readPlugin) {
            $renderedRelations[] = [
                'name' => $readPlugin->getRelationName(),
                'items' => $readPlugin->getRelations($categoryTransfer, $localeTransfer),
            ];
        }

        return $this->viewResponse([
            'category' => $categoryTransfer,
            'renderedRelations' => $renderedRelations,
        ]);
    }
}
