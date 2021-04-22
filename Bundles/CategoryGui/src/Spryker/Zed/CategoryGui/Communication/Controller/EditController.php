<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Controller;

use Spryker\Service\UtilText\Model\Url\Url;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CategoryGui\Communication\CategoryGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\CategoryGui\Persistence\CategoryGuiRepositoryInterface getRepository()
 */
class EditController extends CategoryAbstractController
{
    protected const REQUEST_PARAM_ID_CATEGORY = 'id-category';

    /**
     * @uses \Spryker\Zed\CategoryGui\Communication\Controller\ListController::indexAction()
     */
    protected const ROUTE_CATEGORY_LIST = '/category-gui/list';
    protected const ROUTE_CATEGORY_EDIT = '/category-gui/edit';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $categoryTransfer = $this->getFactory()
            ->createCategoryEditDataProvider()
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
            $categoryResponseTransfer = $this->getFactory()
                ->createCategoryUpdateFormHandler()
                ->updateCategory($form->getData());

            if ($categoryResponseTransfer->getIsSuccessful()) {
                $this->addSuccessMessages($categoryResponseTransfer->getMessages());

                return $this->redirectResponse(
                    $this->createSuccessRedirectUrl($categoryResponseTransfer->getCategoryOrFail()->getIdCategoryOrFail())
                );
            }

            $this->addErrorMessages($categoryResponseTransfer->getMessages());
        }

        return $this->viewResponse([
            'categoryForm' => $form->createView(),
            'currentLocale' => $this->getCurrentLocale()->getLocaleName(),
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
            static::ROUTE_CATEGORY_EDIT,
            [
                static::REQUEST_PARAM_ID_CATEGORY => $idCategory,
            ]
        );

        return $url->build();
    }
}
