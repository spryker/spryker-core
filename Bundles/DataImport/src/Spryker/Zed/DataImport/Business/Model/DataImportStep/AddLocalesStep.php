<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\DataImportStep;

use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class AddLocalesStep implements DataImportStepInterface
{
    public const KEY_LOCALES = 'locales';

    /**
     * @var array
     */
    protected $locales = [];

    /**
     * @var array
     */
    protected $availableLocales;

    /**
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(Store $store)
    {
        $this->availableLocales = $this->getLocales($store);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        if (empty($this->locales)) {
            $localeEntityCollection = SpyLocaleQuery::create()
                ->filterByLocaleName($this->availableLocales, Criteria::IN)
                ->find();

            foreach ($localeEntityCollection as $localeEntity) {
                $this->locales[$localeEntity->getLocaleName()] = $localeEntity->getIdLocale();
            }
        }

        $dataSet[static::KEY_LOCALES] = $this->locales;
    }

    /**
     * @param \Spryker\Shared\Kernel\Store $store
     *
     * @return string[]
     */
    protected function getLocales(Store $store): array
    {
        $locales = $store->getLocales();
        foreach ($store->getStoresWithSharedPersistence() as $storeName) {
            $locales = array_merge($locales, $store->getLocalesPerStore($storeName));
        }

        return $locales;
    }
}
