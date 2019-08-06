<?php
/**
 * Created by PhpStorm.
 * User: kravchenko
 * Date: 2019-08-01
 * Time: 15:10
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Form\Provider;


use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToCurrencyFacadeInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToPriceProductFacadeInterface;
use Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToStoreFacadeInterface;

class PriceProductScheduleFormDataProvider
{
    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToPriceProductFacadeInterface
     */
    protected $priceProductFacade;
    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @param \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToCurrencyFacadeInterface $currencyFacade
     */
    public function __construct(
        PriceProductScheduleGuiToPriceProductFacadeInterface $priceProductFacade,
        PriceProductScheduleGuiToStoreFacadeInterface $storeFacade,
        PriceProductScheduleGuiToCurrencyFacadeInterface $currencyFacade
    )
    {
        $this->priceProductFacade = $priceProductFacade;
        $this->storeFacade = $storeFacade;
        $this->currencyFacade = $currencyFacade;
    }

    /**
     * @return array
     */
    public function getPriceTypeValues(): array
    {
        $priceTypes = $this->priceProductFacade->getPriceTypeValues();
        $result = [];

        foreach ($priceTypes as $priceType) {
            $result[$priceType->getIdPriceType()] = $priceType->getName();
        }

        return $result;
    }

    /**
     * @return array
     */
    public function getStoreValues(): array
    {
        $storeCollection = $this->storeFacade->getAllStores();
        $result = [];

        foreach ($storeCollection as $storeTransfer) {
            $result[$storeTransfer->getIdStore()] = $storeTransfer->getName();
        }

        return $result;
    }

    /**
     * @param int|null $idSTore
     *
     * @return array
     */
    public function getCurrencyValues(?int $idStore): array
    {

        if ($idStore === null) {
            return [];
        }

        $result = [];
        $storeWithCurrenciesCollection = $this->currencyFacade->getAllStoresWithCurrencies();
        foreach ($storeWithCurrenciesCollection as $storeWithCurrencyTransfer) {
            if ($storeWithCurrencyTransfer->getStore()->getIdStore() !== $idStore) {
                continue;
            }
            foreach ($storeWithCurrencyTransfer->getCurrencies() as $currencyTransfer) {
                $result[$currencyTransfer->getIdCurrency()] = $currencyTransfer->getName();
            }
        }

        return $result;
    }

    public function getData(): PriceProductScheduleTransfer
    {
        return new PriceProductScheduleTransfer();
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            'data_class' => PriceProductScheduleTransfer::class,
        ];
    }
}
