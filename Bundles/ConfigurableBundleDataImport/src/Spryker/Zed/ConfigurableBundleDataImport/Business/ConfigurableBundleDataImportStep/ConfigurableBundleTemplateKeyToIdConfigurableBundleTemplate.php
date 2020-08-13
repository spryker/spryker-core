<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ConfigurableBundleDataImport\Business\ConfigurableBundleDataImportStep;

use Orm\Zed\ConfigurableBundle\Persistence\Map\SpyConfigurableBundleTemplateTableMap;
use Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateQuery;
use Spryker\Zed\ConfigurableBundleDataImport\Business\DataSet\ConfigurableBundleTemplateSlotDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class ConfigurableBundleTemplateKeyToIdConfigurableBundleTemplate implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected static $idConfigurableBundleTemplateBuffer = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $configurableBundleTemplateKey = $dataSet[ConfigurableBundleTemplateSlotDataSetInterface::COLUMN_CONFIGURABLE_BUNDLE_TEMPLATE_KEY];

        if (!isset(static::$idConfigurableBundleTemplateBuffer[$configurableBundleTemplateKey])) {
            /** @var int|null $idConfigurableBundleTemplate */
            $idConfigurableBundleTemplate = $this->createConfigurableBundleTemplateQuery()
                ->select([SpyConfigurableBundleTemplateTableMap::COL_ID_CONFIGURABLE_BUNDLE_TEMPLATE])
                ->findOneByKey($configurableBundleTemplateKey);

            if (!$idConfigurableBundleTemplate) {
                throw new EntityNotFoundException(sprintf('Could not find configurable bundle template by key "%s"', $configurableBundleTemplateKey));
            }

            static::$idConfigurableBundleTemplateBuffer[$configurableBundleTemplateKey] = $idConfigurableBundleTemplate;
        }

        $dataSet[ConfigurableBundleTemplateSlotDataSetInterface::ID_CONFIGURABLE_BUNDLE_TEMPLATE] = static::$idConfigurableBundleTemplateBuffer[$configurableBundleTemplateKey];
    }

    /**
     * @module ConfigurableBundle
     *
     * @return \Orm\Zed\ConfigurableBundle\Persistence\SpyConfigurableBundleTemplateQuery
     */
    protected function createConfigurableBundleTemplateQuery(): SpyConfigurableBundleTemplateQuery
    {
        return SpyConfigurableBundleTemplateQuery::create();
    }
}
