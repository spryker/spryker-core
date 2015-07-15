<?php
namespace SprykerFeature\Zed\Country\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Country\Communication\Table\CountryTable;
use Symfony\Component\HttpFoundation\JsonResponse;

class IndexController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction()
    {
        /** @var CountryTable $table */
        $table = $this->getDependencyContainer()->createCountryTable();
        $table->init();

        return $this->viewResponse([
            'countryTable' => $table
        ]);
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
