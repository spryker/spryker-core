<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule;

use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToStoreFacadeInterface;
use Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface;

class PriceProductScheduleReader implements PriceProductScheduleReaderInterface
{
    /**
     * @var \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface;
     */
    protected $priceProductScheduleRepository;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface $priceProductScheduleRepository
     * @param \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        PriceProductScheduleRepositoryInterface $priceProductScheduleRepository,
        PriceProductScheduleToStoreFacadeInterface $storeFacade
    ) {
        $this->priceProductScheduleRepository = $priceProductScheduleRepository;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer[]
     */
    public function findPriceProductSchedulesToDisable(): array
    {
        return $this->priceProductScheduleRepository->findPriceProductSchedulesToDisable();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer[]
     */
    public function findSimilarPriceProductSchedulesToDisable(
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): array {
        return $this->priceProductScheduleRepository->findSimilarPriceProductSchedulesToDisable($priceProductScheduleTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer[]
     */
    public function findPriceProductSchedulesToEnableForCurrentStore(): array
    {
        $storeTransfer = $this->storeFacade->getCurrentStore();
        return $this->priceProductScheduleRepository->findPriceProductSchedulesToEnableByStore($storeTransfer);
    }
}
