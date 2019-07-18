<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\SharedCartDataImport\Business\SharedCartImportStep;

use Orm\Zed\SharedCart\Persistence\Map\SpyQuotePermissionGroupTableMap;
use Orm\Zed\SharedCart\Persistence\SpyQuotePermissionGroupQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\SharedCartDataImport\Business\DataSet\SharedCartDataSetInterface;

class QuotePermissionGroupNameToIdQuotePermissionGroupStep implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected $idPermissionGroupCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $quotePermissionGroupName = $dataSet[SharedCartDataSetInterface::PERMISSION_GROUP_NAME];
        if (!isset($this->idPermissionGroupCache[$quotePermissionGroupName])) {
            $quotePermissionGroupQuery = new SpyQuotePermissionGroupQuery();
            $idPermissionGroup = $quotePermissionGroupQuery
                ->select(SpyQuotePermissionGroupTableMap::COL_ID_QUOTE_PERMISSION_GROUP)
                ->findOneByName($quotePermissionGroupName);

            if (!$idPermissionGroup) {
                throw new EntityNotFoundException(sprintf('Could not find quote permission group by name "%s"', $quotePermissionGroupName));
            }

            $this->idPermissionGroupCache[$quotePermissionGroupName] = $idPermissionGroup;
        }

        $dataSet[SharedCartDataSetInterface::ID_PERMISSION_GROUP] = $this->idPermissionGroupCache[$quotePermissionGroupName];
    }
}
