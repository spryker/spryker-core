<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Collector\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;

interface CollectorToLocaleInterface
{

    /**
     * @return LocaleTransfer
     */
    public function getCurrentLocale();

}
