<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Dependency\Facade;

interface TranslatorToLocaleFacadeInterface
{
    /**
     * @return string
     */
    public function getCurrentLocaleName();

    /**
     * @return array
     */
    public function getSupportedLocaleCodes(): array;
}
