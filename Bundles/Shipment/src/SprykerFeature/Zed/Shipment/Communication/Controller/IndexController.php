<?php
namespace SprykerFeature\Zed\Shipment\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class IndexController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction()
    {

        return $this->viewResponse();
    }

    /**
     * @return JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getDependencyContainer()->createCountryTable();
        $table->init();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }
}
