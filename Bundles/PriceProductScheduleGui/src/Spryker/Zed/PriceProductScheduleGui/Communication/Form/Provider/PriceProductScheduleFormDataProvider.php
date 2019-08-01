<?php
/**
 * Created by PhpStorm.
 * User: kravchenko
 * Date: 2019-08-01
 * Time: 15:10
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Form\Provider;


use Generated\Shared\Transfer\PriceProductScheduleTransfer;
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
     * @param \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToPriceProductFacadeInterface $priceProductFacade
     * @param \Spryker\Zed\PriceProductScheduleGui\Dependency\Facade\PriceProductScheduleGuiToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        PriceProductScheduleGuiToPriceProductFacadeInterface $priceProductFacade,
        PriceProductScheduleGuiToStoreFacadeInterface $storeFacade
    )
    {
        $this->priceProductFacade = $priceProductFacade;
        $this->storeFacade = $storeFacade;
    }

    public function getPriceTypeValues(): array
    {
        $priceTypes = $this->priceProductFacade->getPriceTypeValues();
        $result = [];

        foreach ($priceTypes as $priceType) {
            $result[$priceType->getIdPriceType()] = $priceType->getName();
        }

        return $result;
    }

    public function getStoreValues()
    {
        $storeCollection = $this->storeFacade->getAllStores();
        $result = [];

        foreach ($storeCollection as $storeTransfer) {
            $result[$storeTransfer->getIdStore()] = $storeTransfer->getName();
        }

        return $result;
    }

    public function getData(): PriceProductScheduleTransfer
    {
        return new PriceProductScheduleTransfer();
    }
}
