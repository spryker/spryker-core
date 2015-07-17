<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Category\Business\CategoryFacade;
use SprykerFeature\Zed\Category\Communication\CategoryDependencyContainer;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @method CategoryDependencyContainer getDependencyContainer()
 * @method CategoryFacade getFacade()
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
        $categoryFacade = $this->getFacade();

        return $this->streamedResponse(
            function () use ($categoryFacade) {
                echo $categoryFacade->renderCategoryTreeVisual();
            }
        );
    }

}
