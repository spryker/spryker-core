<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Communication\Controller;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Category\Business\CategoryFacade;
use SprykerFeature\Zed\Category\Communication\CategoryDependencyContainer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method CategoryDependencyContainer getDependencyContainer()
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
        $form = $this->getDependencyContainer()->createCategoryForm($request);

        $form->init();

        if ($form->isValid()) {
            $locale = $this->getLocale();
            $category = new CategoryTransfer();
            $category->fromArray($form->getRequestData());

            if (is_null($category->getIdCategory())) {
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
        $form = $this->getDependencyContainer()->createCategoryNodeForm($request);

        $form->init();

        if ($form->isValid()) {
            $locale = $this->getLocale();
            $categoryNode = new NodeTransfer();
            $categoryNode->fromArray($form->getRequestData());

            if (is_null($categoryNode->getIdCategoryNode())) {
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
        return $this->getDependencyContainer()->getCurrentLocale();
    }

}
