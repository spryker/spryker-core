<?php


namespace Spryker\Zed\CmsBlockGui\Communication\Controller;


use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\CmsBlockGui\Communication\CmsBlockGuiCommunicationFactory getFactory()
 */
class ListBlockController extends AbstractController
{

    /**
     * @return array
     */
    public function indexAction()
    {
        $cmsBlockTable = $this->getFactory()
            ->createCmsBlockTable();

        return [
            'blocks' => $cmsBlockTable->render(),
        ];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getFactory()
            ->createCmsBlockTable();

        return $this->jsonResponse($table->fetchData());
    }
}