<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Currency\Persistence\CurrencyQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Currency\Persistence\CurrencyRepositoryInterface getRepository()
 * @method \Spryker\Zed\Currency\Business\CurrencyFacadeInterface getFacade()
 */
class CurrenciesForStoreController extends AbstractController
{
    protected const KEY_ID_STORE = 'idStore';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $idStore = $request->request->get(static::KEY_ID_STORE);

        if (!$idStore) {
            return $this->jsonResponse([]);
        }

        $idStore = $this->castId($idStore);
        $storeWithCurrencyTransfer = $this->getFacade()->getStoreWithCurrenciesByIdStore($idStore);

        return $this->jsonResponse($storeWithCurrencyTransfer->toArray());
    }
}
