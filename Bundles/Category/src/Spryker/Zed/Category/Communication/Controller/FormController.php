<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Category\Communication\Controller;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Category\Business\CategoryFacade;
use Spryker\Zed\Category\Communication\CategoryCommunicationFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method CategoryCommunicationFactory getCommunicationFactory()
 * @method CategoryFacade getFacade()
 */
class FormController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function categoryAction(Request $request)
    {
        $form = $this->getCommunicationFactory()->createCategoryForm($request);

        $form->init();

        if ($form->isValid()) {
            $locale = $this->getLocale();
            $category = new CategoryTransfer();
            $category->fromArray($form->getRequestData());

            if ($category->getIdCategory() === null) {
                $this->getFacade()->createCategory($category, $locale);
            } else {
                $this->getFacade()->updateCategory($category, $locale);
            }
        }

        return $this->jsonResponse($form->renderData());
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function categoryNodeAction(Request $request)
    {
        $form = $this->getCommunicationFactory()->createCategoryNodeForm($request);

        $form->init();

        if ($form->isValid()) {
            $locale = $this->getLocale();
            $categoryNode = new NodeTransfer();
            $categoryNode->fromArray($form->getRequestData());

            if ($categoryNode->getIdCategoryNode() === null) {
                $this->getFacade()->createCategoryNode($categoryNode, $locale);
            } else {
                $this->getFacade()->updateCategoryNode($categoryNode, $locale);
            }
        }

        return $this->jsonResponse($form->renderData());
    }

    /**
     * @return LocaleTransfer
     */
    protected function getLocale()
    {
        return $this->getCommunicationFactory()->getCurrentLocale();
    }

}
