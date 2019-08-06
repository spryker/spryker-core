<?php
/**
 * Created by PhpStorm.
 * User: kravchenko
 * Date: 2019-08-05
 * Time: 10:41
 */

namespace Spryker\Zed\Currency\Communication\Controller;


use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Currency\Business\CurrencyFacadeInterface getFacade()
 */
class CurrenciesForStoreController extends AbstractController
{
    public function indexAction(Request $request)
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
