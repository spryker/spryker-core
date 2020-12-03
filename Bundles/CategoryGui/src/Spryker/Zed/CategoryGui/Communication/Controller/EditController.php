<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Controller;

use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\CategoryGui\Communication\Exception\CategoryUrlExistsException;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CategoryGui\Communication\CategoryGuiCommunicationFactory getFactory()
 */
class EditController extends AbstractController
{
    protected const REQUEST_PARAM_ID_CATEGORY = 'id-category';

    /**
     * @uses \Spryker\Zed\CategoryGui\Communication\Controller\ListController::indexAction()
     */
    protected const ROUTE_CATEGORY_LIST = '/category-gui/list';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $categoryFacade = $this->getFactory()->getCategoryFacade();

        $categoryFacade->syncCategoryTemplate();

        $categoryTransfer = $this->getFactory()
            ->createCategoryEditFormDataProvider()
            ->getData($this->castId($request->get(static::REQUEST_PARAM_ID_CATEGORY)));

        if ($categoryTransfer === null) {
            $this->addErrorMessage("Category with id %s doesn't exist", [
                '%s' => $request->get(static::REQUEST_PARAM_ID_CATEGORY),
            ]);

            return $this->redirectResponse(static::ROUTE_CATEGORY_LIST);
        }

        $form = $this->getFactory()
            ->createCategoryEditForm($categoryTransfer)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryTransfer = $form->getData();

            try {
                $categoryFacade->update($categoryTransfer);
                $this->addSuccessMessage('The category was updated successfully.');

                return $this->redirectResponse(
                    $this->createSuccessRedirectUrl($categoryTransfer->getIdCategory())
                );
            } catch (CategoryUrlExistsException $e) {
                $this->addErrorMessage($e->getMessage());
            }
        }

        return $this->viewResponse([
            'categoryForm' => $form->createView(),
            'currentLocale' => $this->getFactory()->getLocaleFacade()->getCurrentLocale()->getLocaleName(),
            'idCategory' => $this->castId($request->query->get(static::REQUEST_PARAM_ID_CATEGORY)),
            'categoryFormTabs' => $this->getFactory()->createCategoryFormTabs()->createView(),
        ]);
    }

    /**
     * @param int $idCategory
     *
     * @return string
     */
    protected function createSuccessRedirectUrl(int $idCategory): string
    {
        $url = Url::generate(
            '/category-gui/edit',
            [
                static::REQUEST_PARAM_ID_CATEGORY => $idCategory,
            ]
        );

        return $url->build();
    }
}
