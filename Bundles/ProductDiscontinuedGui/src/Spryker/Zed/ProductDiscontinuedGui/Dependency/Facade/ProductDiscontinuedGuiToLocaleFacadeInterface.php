<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedGui\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;

interface ProductDiscontinuedGuiToLocaleFacadeInterface
{
    /**
     * @param string $localeName
     *
     * @throws \Spryker\Zed\Locale\Business\Exception\MissingLocaleException
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function getLocale($localeName): LocaleTransfer;

    /**
     * @return array
     */
    public function getAvailableLocales(): array;
}
