<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;

interface ConfigurableBundleTranslationExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\LocaleTransfer[] $localeTransfers
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer
     */
    public function expandConfigurableBundleTemplateWithTranslations(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer,
        ArrayObject $localeTransfers
    ): ConfigurableBundleTemplateTransfer;

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\LocaleTransfer[] $localeTransfers
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer
     */
    public function expandConfigurableBundleTemplateSlotWithTranslations(
        ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer,
        ArrayObject $localeTransfers
    ): ConfigurableBundleTemplateSlotTransfer;
}
