<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedRequest\Dependency\Facade;

interface ZedRequestToStoreInterface
{

    /**
     * @param string $isoCode
     *
     * @return void
     */
    public function setCurrencyIsoCode($isoCode);

    /**
     * @param string $localeCode
     *
     * @return void
     */
    public function setCurrentLocale($localeCode);

}
