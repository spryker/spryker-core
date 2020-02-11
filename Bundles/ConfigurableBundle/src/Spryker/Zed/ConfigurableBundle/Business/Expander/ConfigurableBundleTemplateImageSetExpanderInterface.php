<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;

interface ConfigurableBundleTemplateImageSetExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\LocaleTransfer[] $localeTransfers
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer
     */
    public function expandConfigurableBundleTemplateWithImageSets(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer,
        ArrayObject $localeTransfers
    ): ConfigurableBundleTemplateTransfer;
}
