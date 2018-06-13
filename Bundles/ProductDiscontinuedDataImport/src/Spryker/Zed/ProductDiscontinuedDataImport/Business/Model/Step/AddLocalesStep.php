<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedDataImport\Business\Model\Step;

use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductDiscontinuedDataImport\Business\Model\DataSet\ProductDiscontinuedDataSetInterface;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

class AddLocalesStep implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected $locales = [];

    /**
     * @var int[]
     */
    protected $availableLocales = [];

    /**
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(Store $store)
    {
        $this->availableLocales = $store->getLocales();
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

        $dataSet[ProductDiscontinuedDataSetInterface::KEY_LOCALES] = $this->locales;
    }
}
