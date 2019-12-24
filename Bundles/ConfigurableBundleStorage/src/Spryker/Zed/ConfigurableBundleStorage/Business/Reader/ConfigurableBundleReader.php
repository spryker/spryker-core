<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleStorage\Business\Reader;

use Generated\Shared\Transfer\ConfigurableBundleTemplateFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Spryker\Zed\ConfigurableBundleStorage\Dependency\Facade\ConfigurableBundleStorageToConfigurableBundleFacadeInterface;

class ConfigurableBundleReader implements ConfigurableBundleReaderInterface
{
    /**
     * @var \Spryker\Zed\ConfigurableBundleStorage\Dependency\Facade\ConfigurableBundleStorageToConfigurableBundleFacadeInterface
     */
    protected $configurableBundleFacade;

    /**
     * @param \Spryker\Zed\ConfigurableBundleStorage\Dependency\Facade\ConfigurableBundleStorageToConfigurableBundleFacadeInterface $configurableBundleFacade
     */
    public function __construct(ConfigurableBundleStorageToConfigurableBundleFacadeInterface $configurableBundleFacade)
    {
        $this->configurableBundleFacade = $configurableBundleFacade;
    }

    /**
     * @param int[] $configurableBundleTemplateIds
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer[]
     */
    public function getConfigurableBundleTemplates(array $configurableBundleTemplateIds): array
    {
        $configurableBundleTemplateFilterTransfer = (new ConfigurableBundleTemplateFilterTransfer())
            ->setConfigurableBundleTemplateIds($configurableBundleTemplateIds);

        return $this->configurableBundleFacade
            ->getConfigurableBundleTemplateCollection($configurableBundleTemplateFilterTransfer)
            ->getConfigurableBundleTemplates()
            ->getArrayCopy();
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer[]
     */
    public function getConfigurableBundleTemplateSlots(ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer): array
    {
        $configurableBundleTemplateSlotFilterTransfer = (new ConfigurableBundleTemplateSlotFilterTransfer())
            ->setIdConfigurableBundleTemplate($configurableBundleTemplateTransfer->getIdConfigurableBundleTemplate());

        return $this->configurableBundleFacade
            ->getConfigurableBundleTemplateSlotCollection($configurableBundleTemplateSlotFilterTransfer)
            ->getConfigurableBundleTemplateSlots()
            ->getArrayCopy();
    }
}
