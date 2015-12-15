<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductOption\Dependency\Facade;

use Spryker\Zed\Locale\Business\Exception\MissingLocaleException;
use Generated\Shared\Transfer\LocaleTransfer;

interface ProductOptionToLocaleInterface
{

    /**
     * @param string $localeName
     *
     * @return bool
     */
    public function hasLocale($localeName);

    /**
     * @param string $localeName
     *
     * @throws MissingLocaleException
     *
     * @return LocaleTransfer
     */
    public function getLocale($localeName);

}
