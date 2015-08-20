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

        return $this->viewResponse([
            'countryTable' => $table->render(),
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getDependencyContainer()->createCountryTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

}
