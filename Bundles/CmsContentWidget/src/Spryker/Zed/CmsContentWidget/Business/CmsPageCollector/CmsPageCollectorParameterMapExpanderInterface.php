<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsContentWidget\Business\CmsPageCollector;

use Generated\Shared\Transfer\LocaleTransfer;

interface CmsPageCollectorParameterMapExpanderInterface
{

    /**
     * @param array $collectedData
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function map(array $collectedData, LocaleTransfer $localeTransfer);

}
