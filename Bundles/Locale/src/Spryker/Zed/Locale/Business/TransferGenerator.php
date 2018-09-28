<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Locale\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Locale\Persistence\SpyLocale;

class TransferGenerator implements TransferGeneratorInterface
{
    /**
     * @param \Orm\Zed\Locale\Persistence\SpyLocale $localeEntity
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    public function convertLocale(SpyLocale $localeEntity)
    {
        return (new LocaleTransfer())
            ->fromArray($localeEntity->toArray(), true);
    }

    /**
     * @deprecated Invalid collection method. Use convertLocale() directly.
     *
     * @param \Orm\Zed\Locale\Persistence\SpyLocale $localeEntityList
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer[]
     */
    public function convertLocaleCollection(SpyLocale $localeEntityList)
    {
        /** @var \Orm\Zed\Locale\Persistence\SpyLocale[] $localeEntities */
        $localeEntities = $localeEntityList;

        $transferList = [];
        foreach ($localeEntities as $localeEntity) {
            $transferList[] = $this->convertLocale($localeEntity);
        }

        return $transferList;
    }
}
