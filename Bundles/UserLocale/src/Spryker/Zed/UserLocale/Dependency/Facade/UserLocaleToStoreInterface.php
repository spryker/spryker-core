<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserLocale\Dependency\Facade;

interface UserLocaleToStoreInterface
{
    /**
     * @param string $currentLocale
     *
     * @return void
     */
    public function setCurrentLocale($currentLocale);

    /**
     * @return string
     */
    public function getCurrentLocale();
}
