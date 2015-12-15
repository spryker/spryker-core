<?php

namespace Spryker\Zed\Country\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Country\Communication\Table\CountryTable;
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
