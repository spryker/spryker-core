<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundle\Business\Generator;

use Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;

interface ConfigurableBundleNameGeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return string
     */
    public function generateTemplateName(ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer): string;

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer
     *
     * @return string
     */
    public function generateTemplateSlotName(ConfigurableBundleTemplateSlotTransfer $configurableBundleTemplateSlotTransfer): string;
}
