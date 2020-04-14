<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundlePageSearch\Business\Mapper;

use Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\LocaleTransfer;

interface ConfigurableBundleTemplatePageSearchMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer
     */
    public function mapConfigurableBundleTemplateTransferToPageSearchTransfer(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer,
        LocaleTransfer $localeTransfer,
        ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer
    ): ConfigurableBundleTemplatePageSearchTransfer;
}
