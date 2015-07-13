<?php
namespace SprykerFeature\Zed\Country\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Country\Business\CountryFacade;
use SprykerFeature\Zed\Country\Communication\CountryDependencyContainer;
use SprykerFeature\Zed\Country\Persistence\CountryQueryContainerInterface;

/**
 * @method CountryDependencyContainer getDependencyContainer()
 * @method CountryFacade getFacade()
 * @method CountryQueryContainerInterface getQueryContainer()
 */
class IndexController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction()
    {
        $table = $this->getDependencyContainer()->createCountryTable();
        $table->init();

        return $this->viewResponse(
            ['countryTable' => $table]
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
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
