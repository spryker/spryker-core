<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Reader;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTemplateSlotProductListExpanderInterface;
use Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTranslationExpanderInterface;
use Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface;

class ConfigurableBundleTemplateSlotReader implements ConfigurableBundleTemplateSlotReaderInterface
{
    /**
     * @var \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface
     */
    protected $configurableBundleRepository;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTranslationExpanderInterface
     */
    protected $configurableBundleTranslationExpander;

    /**
     * @var \Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTemplateSlotProductListExpanderInterface
     */
    protected $configurableBundleTemplateSlotProductListExpander;

    /**
     * @param \Spryker\Zed\ConfigurableBundle\Persistence\ConfigurableBundleRepositoryInterface $configurableBundleRepository
     * @param \Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTranslationExpanderInterface $configurableBundleTranslationExpander
     * @param \Spryker\Zed\ConfigurableBundle\Business\Expander\ConfigurableBundleTemplateSlotProductListExpanderInterface $configurableBundleTemplateSlotProductListExpander
     */
    public function __construct(
        ConfigurableBundleRepositoryInterface $configurableBundleRepository,
        ConfigurableBundleTranslationExpanderInterface $configurableBundleTranslationExpander,
        ConfigurableBundleTemplateSlotProductListExpanderInterface $configurableBundleTemplateSlotProductListExpander
    ) {
        $this->configurableBundleRepository = $configurableBundleRepository;
        $this->configurableBundleTranslationExpander = $configurableBundleTranslationExpander;
        $this->configurableBundleTemplateSlotProductListExpander = $configurableBundleTemplateSlotProductListExpander;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer|null
     */
    public function findConfigurableBundleTemplateSlot(
        ConfigurableBundleTemplateSlotFilterTransfer $configurableBundleTemplateSlotFilterTransfer
    ): ?ConfigurableBundleTemplateSlotTransfer {
        $configurableBundleTemplateSlotTransfer = $this->configurableBundleRepository
            ->findConfigurableBundleTemplateSlot($configurableBundleTemplateSlotFilterTransfer);

        if (!$configurableBundleTemplateSlotTransfer) {
            return null;
        }

        $configurableBundleTemplateSlotTransfer = $this->configurableBundleTemplateSlotProductListExpander
            ->expandConfigurableBundleTemplateSlotWithProductList($configurableBundleTemplateSlotTransfer);

        return $configurableBundleTemplateSlotTransfer;
    }
}
