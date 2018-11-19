<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \Spryker\Zed\Country\Communication\CountryCommunicationFactory getFactory()
 * @method \Spryker\Zed\Country\Persistence\CountryQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Country\Persistence\CountryRepositoryInterface getRepository()
 * @method \Spryker\Zed\Country\Business\CountryFacadeInterface getFacade()
 */
class IndexController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction()
    {
        $table = $this->getFactory()->createCountryTable();

        return $this->viewResponse([
            'countryTable' => $table->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getFactory()->createCountryTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }
}
