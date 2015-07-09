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
     *
     */
    public function indexAction()
    {
        $table = $this->getDependencyContainer()->createDetailsTable();
        $table->init();

        return $this->viewResponse(
            ['gui' => ['countryTable' => $table]] // TODO remove gui
        );
    }

//    public function ajaxAction()
//    {
//        $table = $this->getDependencyContainer()->createDetailsTable();
//        $table->init();
//
//        return $this->jsonResponse(
//            $table->getData()
//        );
//    }

}