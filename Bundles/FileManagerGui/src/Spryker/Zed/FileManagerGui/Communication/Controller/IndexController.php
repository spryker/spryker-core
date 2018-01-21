<?php

namespace Spryker\Zed\FileManagerGui\Communication\Controller;

use Spryker\Zed\FileManagerGui\Communication\FileManagerGuiCommunicationFactory;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method FileManagerGuiCommunicationFactory getFactory()
 */
class IndexController extends AbstractController
{

    /**
     * @return array
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function indexAction()
    {
        $fileTable = $this->getFactory()
            ->createFileTable();

        return [
            'files' => $fileTable->render(),
        ];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function tableAction()
    {
        $table = $this->getFactory()
            ->createFileTable();

        return $this->jsonResponse($table->fetchData());
    }

}