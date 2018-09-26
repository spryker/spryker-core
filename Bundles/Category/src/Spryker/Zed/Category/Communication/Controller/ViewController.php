<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Communication\Controller;

use Spryker\Zed\Category\Business\Exception\MissingCategoryException;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Category\Business\CategoryFacadeInterface getFacade()
 * @method \Spryker\Zed\Category\Communication\CategoryCommunicationFactory getFactory()
 * @method \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface getQueryContainer()
 */
class ViewController extends AbstractController
{
    const QUERY_PARAM_ID_CATEGORY = 'id-category';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idCategory = $request->query->getInt(static::QUERY_PARAM_ID_CATEGORY);

        try {
            $categoryTransfer = $this->getFacade()
                ->read($idCategory);
        } catch (MissingCategoryException $exception) {
            $this->addErrorMessage(sprintf('Category with id %s doesn\'t exist', $idCategory));

            return $this->redirectResponse('/category/root');
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
