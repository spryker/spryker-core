<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SearchPage\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\SearchPage\Communication\SearchPageDependencyContainer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method SearchPageDependencyContainer getDependencyContainer()
 */
class PageElementController extends AbstractController
{

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function switchActiveStateAction(Request $request)
    {
        $contentData = json_decode($request->getContent(), 1);

        if ($request->isMethod('POST')) {
            $idPageElement = (int) $request->get('id');
            $isElementActive = $contentData['value'];

            $this->getDependencyContainer()
                ->getSearchPageFacade()
                ->switchActiveState($idPageElement, $isElementActive)
            ;
        }

        return $this->jsonResponse(['content' => $contentData], 200);
    }

}
