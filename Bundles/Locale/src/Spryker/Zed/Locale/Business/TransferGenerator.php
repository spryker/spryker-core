<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Locale\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Locale\Persistence\SpyLocale;

class TransferGenerator implements TransferGeneratorInterface
{

    /**
     * @param SpyLocale $localeEntity
     *
     * @return LocaleTransfer
     */
    public function convertLocale(SpyLocale $localeEntity)
    {
        return (new LocaleTransfer())
            ->fromArray($localeEntity->toArray(), true);
    }

    /**
     * @param SpyLocale $localeEntityList
     *
     * @return LocaleTransfer[]
     */
    public function convertLocaleCollection(SpyLocale $localeEntityList)
    {
        $transferList = [];
        foreach ($localeEntityList as $localeEntity) {
            $transferList[] = $this->convertLocale($localeEntity);
        }

        return $transferList;
    }

}
