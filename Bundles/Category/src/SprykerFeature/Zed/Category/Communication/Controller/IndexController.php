<?php

namespace SprykerFeature\Zed\Category\Communication\Controller;

use SprykerFeature\Zed\Category\Business\CategoryFacade;
use SprykerFeature\Zed\Category\Communication\CategoryDependencyContainer;
use SprykerFeature\Zed\Category\Persistence\CategoryQueryContainer;
use Symfony\Component\HttpFoundation\JsonResponse;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;

/**
 * @method CategoryDependencyContainer getDependencyContainer()
 * @method CategoryFacade getFacade()
 * @method CategoryQueryContainer getQueryContainer()
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
}
