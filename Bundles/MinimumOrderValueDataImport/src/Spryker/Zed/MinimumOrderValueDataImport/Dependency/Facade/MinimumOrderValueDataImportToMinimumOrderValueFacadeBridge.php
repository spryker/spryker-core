<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValueDataImport\Dependency\Facade;

use Generated\Shared\Transfer\MinimumOrderValueTransfer;

class MinimumOrderValueDataImportToMinimumOrderValueFacadeBridge implements MinimumOrderValueDataImportToMinimumOrderValueFacadeInterface
{
    /**
     * @var \Spryker\Zed\MinimumOrderValue\Business\MinimumOrderValueFacadeInterface
     */
    protected $minimumOrderValueFacade;

    /**
     * @param \Spryker\Zed\MinimumOrderValue\Business\MinimumOrderValueFacadeInterface $minimumOrderValueFacade
     */
    public function __construct($minimumOrderValueFacade)
    {
        $this->minimumOrderValueFacade = $minimumOrderValueFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTransfer
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer
     */
    public function setStoreThreshold(
        MinimumOrderValueTransfer $minimumOrderValueTransfer
    ): MinimumOrderValueTransfer {
        return $this->minimumOrderValueFacade->setStoreThreshold($minimumOrderValueTransfer);
    }
}
