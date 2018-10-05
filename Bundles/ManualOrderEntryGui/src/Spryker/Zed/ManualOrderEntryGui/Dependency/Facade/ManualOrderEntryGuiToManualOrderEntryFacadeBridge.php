<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Dependency\Facade;

class ManualOrderEntryGuiToManualOrderEntryFacadeBridge implements ManualOrderEntryGuiToManualOrderEntryFacadeInterface
{
    /**
     * @var \Spryker\Zed\ManualOrderEntry\Business\ManualOrderEntryFacadeInterface
     */
    protected $manualOrderEntryFacade;

    /**
     * @param \Spryker\Zed\ManualOrderEntry\Business\ManualOrderEntryFacadeInterface $manualOrderEntryFacade
     */
    public function __construct($manualOrderEntryFacade)
    {
        $this->manualOrderEntryFacade = $manualOrderEntryFacade;
    }

    /**
     * @param int $idOrderSource
     *
     * @return \Generated\Shared\Transfer\OrderSourceTransfer
     */
    public function getOrderSourceById($idOrderSource)
    {
        return $this->manualOrderEntryFacade->getOrderSourceById($idOrderSource);
    }

    /**
     * @return \Generated\Shared\Transfer\OrderSourceTransfer[]
     */
    public function getAllOrderSources()
    {
        return $this->manualOrderEntryFacade->getAllOrderSources();
    }
}
