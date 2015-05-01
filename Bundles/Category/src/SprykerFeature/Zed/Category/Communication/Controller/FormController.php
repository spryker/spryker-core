<?php

namespace SprykerFeature\Zed\Category\Communication\Controller;

use SprykerEngine\Shared\Dto\LocaleDto;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Category\Business\CategoryFacade;
use SprykerFeature\Zed\Category\Communication\CategoryDependencyContainer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method CategoryDependencyContainer getDependencyContainer()
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
            $category = new \Generated\Shared\Transfer\CategoryCategoryTransfer();
            $category->fromArray($form->getRequestData());

            if (is_null($category->getIdCategory())) {
                $this->getCategoryFacade()->createCategory($category, $locale);
            } else {
                $this->getCategoryFacade()->updateCategory($category, $locale);
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
            $categoryNode = new \Generated\Shared\Transfer\CategoryCategoryNodeTransfer();
            $categoryNode->fromArray($form->getRequestData());

            if (is_null($categoryNode->getIdCategoryNode())) {
                $this->getCategoryFacade()->createCategoryNode($categoryNode, $locale);
            } else {
                $this->getCategoryFacade()->moveCategoryNode($categoryNode);
            }
        }

        return $this->jsonResponse($form->renderData());
    }

    /**
     * @return CategoryFacade
     */
    protected function getCategoryFacade()
    {
        return $this->getDependencyContainer()->createCategoryFacade();
    }

    /**
     * @return LocaleDto
     */
    protected function getLocale()
    {
        return $this->getDependencyContainer()->getCurrentLocale();
    }
}
