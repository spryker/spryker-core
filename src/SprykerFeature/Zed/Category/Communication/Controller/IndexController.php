<?php

namespace SprykerFeature\Zed\Category\Communication\Controller;

use SprykerFeature\Zed\Category\Communication\CategoryDependencyContainer;
use SprykerFeature\Zed\Category\Persistence\CategoryQueryContainer;
use Symfony\Component\HttpFoundation\JsonResponse;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;

/**
 * @method CategoryDependencyContainer getDependencyContainer()
 */
class IndexController extends AbstractController
{
    public function indexAction()
    {

    }

    /**
     * @return JsonResponse
     */
    public function renderAction()
    {
        $categoryFacade = $this->getDependencyContainer()->createCategoryFacade();

        return $this->streamedResponse(
            function () use ($categoryFacade) {
                echo $categoryFacade->renderCategoryTreeVisual();
            }
        );
    }

    /**
     * @return CategoryQueryContainer
     */
    protected function getQueryContainer()
    {
        return $this->getLocator()->category()->queryContainer();
    }
}
