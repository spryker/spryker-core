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
     * @param \Orm\Zed\Locale\Persistence\SpyLocale $localeEntity
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function convertLocale(SpyLocale $localeEntity);

    /**
     * @param \Orm\Zed\Locale\Persistence\SpyLocale $localeEntityList
     *
     * @return LocaleTransfer[]
     */
    public function convertLocaleCollection(SpyLocale $localeEntityList);

}
