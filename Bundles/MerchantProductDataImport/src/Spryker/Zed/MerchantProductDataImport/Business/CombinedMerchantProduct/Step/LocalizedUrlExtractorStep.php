<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step;

use Spryker\Zed\DataImport\Business\Model\DataImportStep\AddLocalesStep;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\DataSet\MerchantCombinedProductDataSetInterface;

class LocalizedUrlExtractorStep implements DataImportStepInterface
{
    /**
     * @var string
     */
    public const KEY_LOCALIZED_URLS = 'LOCALIZED_URLS';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $localizedUrls = [];
        $localeNames = $this->getLocaleNames($dataSet);

        foreach ($localeNames as $localeName) {
            $localizedKey = $this->buildLocalizedKey(
                MerchantCombinedProductDataSetInterface::KEY_PRODUCT_ABSTRACT_URL_LOCALIZED,
                $localeName,
            );

            $localizedUrls[$localeName] = $dataSet[$localizedKey] ?? null;
        }

        $dataSet[static::KEY_LOCALIZED_URLS] = $localizedUrls;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return array<string>
     */
    protected function getLocaleNames(DataSetInterface $dataSet): array
    {
        /** @phpstan-var array<string> $localeNames */
        $localeNames = array_keys($dataSet[AddLocalesStep::KEY_LOCALES]);

        return $localeNames;
    }

    /**
     * @param string $dataSetKeyPattern
     * @param string $localeName
     *
     * @return string
     */
    protected function buildLocalizedKey(string $dataSetKeyPattern, string $localeName): string
    {
        return strtr(
            $dataSetKeyPattern,
            [
                MerchantCombinedProductDataSetInterface::PLACEHOLDER_LOCALE => $localeName,
            ],
        );
    }
}
