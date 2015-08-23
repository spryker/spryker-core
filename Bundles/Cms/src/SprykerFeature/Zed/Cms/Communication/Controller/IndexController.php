<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Zed\Cms\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;

class IndexController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        $pageTable = $this->getDependencyContainer()
            ->createCmsPageTable()
        ;

        $blockTable = $this->getDependencyContainer()
            ->createCmsBlockTable()
        ;

        $redirectTable = $this->getDependencyContainer()
            ->createCmsRedirectTable()
        ;

        return [
            'pages' => $pageTable->render(),
            'blocks' => $blockTable->render(),
            'redirects' => $redirectTable->render(),
        ];
    }

    /**
     * @return JsonResponse
     */
    public function pageTableAction()
    {
        $table = $this->getDependencyContainer()
            ->createCmsPageTable()
        ;

        return $this->jsonResponse($table->fetchData());
    }

    /**
     * @return JsonResponse
     */
    public function blockTableAction()
    {
        $table = $this->getDependencyContainer()
            ->createCmsBlockTable()
        ;

        return $this->jsonResponse($table->fetchData());
    }

    /**
     * @return JsonResponse
     */
    public function redirectTableAction()
    {
        $table = $this->getDependencyContainer()
            ->createCmsRedirectTable()
        ;

        return $this->jsonResponse($table->fetchData());
    }
}
