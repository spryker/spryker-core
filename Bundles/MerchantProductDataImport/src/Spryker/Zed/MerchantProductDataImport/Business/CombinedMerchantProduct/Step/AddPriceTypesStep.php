<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step;

use Orm\Zed\PriceProduct\Persistence\SpyPriceTypeQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class AddPriceTypesStep implements DataImportStepInterface
{
    /**
     * @var string
     */
    public const KEY_PRICE_TYPES = 'DATA_PRICE_TYPES';

    /**
     * @var array<string, int>
     */
    protected static array $priceTypes = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $dataSet[static::KEY_PRICE_TYPES] = $this->getPriceTypeNameToIdMap();
    }

    /**
     * @return array<string, int>
     */
    protected function getPriceTypeNameToIdMap(): array
    {
        if (!static::$priceTypes) {
            $spyPriceTypes = SpyPriceTypeQuery::create()->find();
            foreach ($spyPriceTypes as $spyPriceType) {
                /** @phpstan-var string $name */
                $name = $spyPriceType->getName();

                /** @phpstan-var int $idPriceType */
                $idPriceType = $spyPriceType->getIdPriceType();

                static::$priceTypes[$name] = $idPriceType;
            }
        }

        return static::$priceTypes;
    }
}
