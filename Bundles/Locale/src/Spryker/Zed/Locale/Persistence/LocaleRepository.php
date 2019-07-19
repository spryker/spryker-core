<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Locale\Persistence;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Locale\Persistence\LocalePersistenceFactory getFactory()
 */
class LocaleRepository extends AbstractRepository implements LocaleRepositoryInterface
{
    /**
     * @param string[] $localeNames
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer[]
     */
    public function getLocaleTransfersByLocaleNames(array $localeNames): array
    {
        $localeEntities = $this->getFactory()->createLocaleQuery()
            ->filterByLocaleName_In($localeNames)
            ->find();

        return $this->mapLocaleEntitiesToLocaleTransfers($localeEntities);
    }

    /**
     * @param \Orm\Zed\Locale\Persistence\SpyLocale[]|\Propel\Runtime\Collection\ObjectCollection $localeEntities
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer[]
     */
    protected function mapLocaleEntitiesToLocaleTransfers(ObjectCollection $localeEntities): array
    {
        $localeTransfers = [];
        $localeMapper = $this->getFactory()->createLocaleMapper();

        foreach ($localeEntities as $localeEntity) {
            $localeTransfer = new LocaleTransfer();
            $localeTransfer = $localeMapper->mapLocaleEntityToLocaleTransfer($localeEntity, $localeTransfer);

            $localeTransfers[] = $localeTransfer;
        }

        return $localeTransfers;
    }
}
