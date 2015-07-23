<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Glossary\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Glossary\Communication\GlossaryDependencyContainer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method GlossaryDependencyContainer getDependencyContainer()
 */
class GridController extends AbstractController
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function translationAction(Request $request)
    {
        $grid = $this->getDependencyContainer()->createGlossaryKeyTranslationGrid($request);

        return $this->jsonResponse($grid->renderData());
    }
}
