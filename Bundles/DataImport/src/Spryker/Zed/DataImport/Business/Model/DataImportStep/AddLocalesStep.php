<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\DataImportStep;

use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToStoreFacadeInterface;

class AddLocalesStep implements DataImportStepInterface
{
    /**
     * @var string
     */
    public const KEY_LOCALES = 'locales';

    /**
     * @var array
     */
    protected $locales = [];

    /**
     * @var \Spryker\Zed\DataImport\Dependency\Facade\DataImportToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\DataImport\Dependency\Facade\DataImportToStoreFacadeInterface $storeFacade
     */
    public function __construct(DataImportToStoreFacadeInterface $storeFacade)
    {
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        if (!$this->locales) {
            $localeEntityCollection = SpyLocaleQuery::create()
                ->filterByLocaleName($this->getLocales(), Criteria::IN)
                ->find();

            foreach ($localeEntityCollection as $localeEntity) {
                $this->locales[$localeEntity->getLocaleName()] = $localeEntity->getIdLocale();
            }
        }

        $dataSet[static::KEY_LOCALES] = $this->locales;
    }

    /**
     * @return array<string>
     */
    protected function getLocales(): array
    {
        $locales = [];

        foreach ($this->storeFacade->getAllStores() as $storeTransfers) {
            $locales = array_merge($locales, array_values($storeTransfers->getAvailableLocaleIsoCodes()));
        }

        return $locales;
    }
}
