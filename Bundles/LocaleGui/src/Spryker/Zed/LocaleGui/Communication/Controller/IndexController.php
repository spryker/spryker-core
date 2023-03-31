<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\LocaleGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\LocaleGui\Communication\LocaleGuiCommunicationFactory getFactory()
 */
class IndexController extends AbstractController
{
    /**
     * @uses \Spryker\Zed\LocaleGui\Communication\Table\LocaleStoreTable::PARAM_STORE_ID
     *
     * @var string
     */
    protected const PARAM_STORE_ID = 'store-id';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function assignedLocaleTableAction(Request $request): JsonResponse
    {
        $table = $this->getFactory()->createAssignedLocaleStoreTable($this->getIdStore($request));

        return $this->jsonResponse(
            $table->fetchData(),
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function assignedLocaleTableSelectableAction(Request $request): JsonResponse
    {
        $table = $this->getFactory()->createSelectableAssignedLocaleStoreTable($this->getIdStore($request));

        return $this->jsonResponse(
            $table->fetchData(),
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function availableLocaleTableSelectableAction(Request $request): JsonResponse
    {
        $table = $this->getFactory()->createSelectableAvailableLocaleStoreTable($this->getIdStore($request));

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
