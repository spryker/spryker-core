<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Controller;

use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CategoryGui\Communication\CategoryGuiCommunicationFactory getFactory()
 */
class ViewController extends AbstractController
{
    protected const REQUEST_PARAM_ID_CATEGORY = 'id-category';

    /**
     * @uses \Spryker\Zed\CategoryGui\Communication\Controller\ListController::indexAction()
     */
    protected const ROUTE_CATEGORY_LIST = '/category-gui/list';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $idCategory = $request->query->getInt(static::REQUEST_PARAM_ID_CATEGORY);
        $categoryTransfer = $this->getFactory()->getCategoryFacade()->findCategoryById($idCategory);

        if ($categoryTransfer === null) {
            $this->addErrorMessage("Category with id %s doesn't exist", ['%s' => $idCategory]);

            return $this->redirectResponse(static::ROUTE_CATEGORY_LIST);
        }

        return $this->viewResponse([
            'category' => $categoryTransfer,
            'renderedRelations' => $this->getRenderedRelations($categoryTransfer),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return array
     */
    protected function getRenderedRelations(CategoryTransfer $categoryTransfer): array
    {
        $renderedRelations = [];
        $localeTransfer = $this->getFactory()->getCurrentLocale();

        $categoryRelationReadPlugins = $this->getFactory()
            ->getCategoryRelationReadPlugins();

        foreach ($categoryRelationReadPlugins as $categoryRelationReadPlugin) {
            $renderedRelations[] = [
                'name' => $categoryRelationReadPlugin->getRelationName(),
                'items' => $categoryRelationReadPlugin->getRelations($categoryTransfer, $localeTransfer),
            ];
        }

        return $renderedRelations;
    }
}
