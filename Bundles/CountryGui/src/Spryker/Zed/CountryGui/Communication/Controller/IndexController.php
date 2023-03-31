<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CountryGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\CountryGui\Communication\CountryGuiCommunicationFactory getFactory()
 */
class IndexController extends AbstractController
{
    /**
     * @uses \Spryker\Zed\CountryGui\Communication\Table\CountryStoreTable::PARAM_STORE_ID
     *
     * @var string
     */
    protected const PARAM_STORE_ID = 'store-id';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function assignedCountryTableAction(Request $request): JsonResponse
    {
        $table = $this->getFactory()->createAssignedCountryStoreTable($this->getIdStore($request));

        return $this->jsonResponse(
            $table->fetchData(),
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function assignedCountryTableSelectableAction(Request $request): JsonResponse
    {
        $table = $this->getFactory()->createSelectableAssignedCountryStoreTable($this->getIdStore($request));

        return $this->jsonResponse(
            $table->fetchData(),
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function availableCountryTableSelectableAction(Request $request): JsonResponse
    {
        $table = $this->getFactory()->createSelectableAvailableCountryStoreTable($this->getIdStore($request));

        return $this->jsonResponse(
            $table->fetchData(),
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return int|null
     */
    protected function getIdStore(Request $request): ?int
    {
        $idStore = $request->get(static::PARAM_STORE_ID);

        if ($idStore === null) {
            return null;
        }

        return $this->castId($idStore);
    }
}
