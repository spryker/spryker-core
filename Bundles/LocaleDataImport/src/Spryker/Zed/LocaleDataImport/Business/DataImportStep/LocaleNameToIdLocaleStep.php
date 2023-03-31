<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\LocaleDataImport\Business\DataImportStep;

use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\LocaleDataImport\Business\DataSet\LocaleDataSetInterface;

class LocaleNameToIdLocaleStep implements DataImportStepInterface
{
    /**
     * @var \Orm\Zed\Locale\Persistence\SpyLocaleQuery<mixed>
     */
    protected $localeQuery;

    /**
     * @var array<int>
     */
    protected static $idLocaleCache = [];

    /**
     * @param \Orm\Zed\Locale\Persistence\SpyLocaleQuery<mixed> $localeQuery
     */
    public function __construct(SpyLocaleQuery $localeQuery)
    {
        $this->localeQuery = $localeQuery;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface<string> $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $localeName = $dataSet[LocaleDataSetInterface::COLUMN_LOCALE_NAME];

        if (!isset(static::$idLocaleCache[$localeName])) {
            $localeEntity = $this->localeQuery
                ->clear()
                ->filterByLocaleName($localeName)
                ->findOne();

            if ($localeEntity === null) {
                throw new EntityNotFoundException(sprintf('Locale not found: %s', $localeName));
            }

            static::$idLocaleCache[$localeName] = $localeEntity->getIdLocale();
        }

        $dataSet[LocaleDataSetInterface::ID_LOCALE] = static::$idLocaleCache[$localeName];
    }
}
