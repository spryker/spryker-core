<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Locale\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Locale\Persistence\SpyLocale;

interface TransferGeneratorInterface
{

    /**
     * @param SpyLocale $localeEntity
     *
     * @return LocaleTransfer
     */
    public function convertLocale(SpyLocale $localeEntity);

    /**
     * @param SpyLocale $localeEntityList
     *
     * @return LocaleTransfer[]
     */
    public function convertLocaleCollection(SpyLocale $localeEntityList);

}
