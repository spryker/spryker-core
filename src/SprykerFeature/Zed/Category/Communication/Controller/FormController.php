<?php

namespace SprykerFeature\Zed\Category\Communication\Controller;

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
            $idLocale = $this->getLocaleIdentifier();
            $category = $this->getLocator()->category()->transferCategory();
            $category->fromArray($form->getRequestData());

            if (is_null($category->getIdCategory())) {
                $this->getCategoryFacade()->createCategory($category, $idLocale);
            } else {
                $this->getCategoryFacade()->updateCategory($category, $idLocale);
            }
            $form->setActiveValuesToDefault();
        }

        return $this->jsonResponse($form->toArray());
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
            $idLocale = $this->getLocaleIdentifier();
            $categoryNode = $this->getLocator()->category()->transferCategoryNode();
            $categoryNode->fromArray($form->getRequestData());

            if (is_null($categoryNode->getIdCategoryNode())) {
                $this->getCategoryFacade()->createCategoryNode($categoryNode, $idLocale);
            } else {
                $this->getCategoryFacade()->moveCategoryNode($categoryNode);
            }
            $form->setActiveValuesToDefault();
        }

        return $this->jsonResponse($form->toArray());
    }

    /**
     * @return CategoryFacade
     */
    protected function getCategoryFacade()
    {
        return $this->getDependencyContainer()->createCategoryFacade();
    }

    /**
     * @return int
     */
    protected function getLocaleIdentifier()
    {
        return $this->getDependencyContainer()->createLocaleIdentifier();
    }
}
