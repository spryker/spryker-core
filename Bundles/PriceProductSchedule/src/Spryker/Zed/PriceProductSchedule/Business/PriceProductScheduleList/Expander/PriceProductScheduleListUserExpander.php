<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleList\Expander;

use Generated\Shared\Transfer\PriceProductScheduleListTransfer;
use Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToUserFacadeInterface;

class PriceProductScheduleListUserExpander implements PriceProductScheduleListUserExpanderInterface
{
    /**
     * @var \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToUserFacadeInterface
     */
    protected $userFacade;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Dependency\Facade\PriceProductScheduleToUserFacadeInterface $userFacade
     */
    public function __construct(PriceProductScheduleToUserFacadeInterface $userFacade)
    {
        $this->userFacade = $userFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleListTransfer $priceProductScheduleListTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListTransfer
     */
    public function expand(PriceProductScheduleListTransfer $priceProductScheduleListTransfer): PriceProductScheduleListTransfer
    {
        $userTransfer = $this->userFacade->getCurrentUser();

        $priceProductScheduleListTransfer->setUser($userTransfer)
            ->setFkUser($userTransfer->getIdUser());

        return $priceProductScheduleListTransfer;
    }
}
