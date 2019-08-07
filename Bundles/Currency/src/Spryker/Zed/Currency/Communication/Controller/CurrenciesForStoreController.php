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
 * @method \Spryker\Zed\Currency\Business\CurrencyFacadeInterface getFacade()
 * @method \Spryker\Zed\Currency\Persistence\CurrencyQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Currency\Persistence\CurrencyRepositoryInterface getRepository()
 */
class CurrenciesForStoreController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function indexAction(Request $request): JsonResponse
    {
        $idStore = $this->castId($request->request->get('idStore'));
        $storeWithCurrencyCollection = $this->getFacade()->getAllStoresWithCurrencies();

        $currencies = [];
        $store = [];

        foreach ($storeWithCurrencyCollection as $storeWithCurrencyTransfer) {
            $storeWithCurrencyTransfer->requireStore();
            if ($storeWithCurrencyTransfer->getStore()->getIdStore() !== $idStore) {
                continue;
            }
            $store = $storeWithCurrencyTransfer->getStore()->toArray();

            $currencyCollection = $storeWithCurrencyTransfer->getCurrencies();
            foreach ($currencyCollection as $currencyTransfer) {
                $currencies[] = $currencyTransfer->toArray();
            }
        }
        $result = [
            'currencies' => $currencies,
            'store' => $store,
        ];

        return $this->jsonResponse($result);
    }
}
