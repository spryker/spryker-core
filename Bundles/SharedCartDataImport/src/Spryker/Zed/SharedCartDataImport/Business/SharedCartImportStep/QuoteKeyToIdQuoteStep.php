<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\SharedCartDataImport\Business\SharedCartImportStep;

use Orm\Zed\Quote\Persistence\Map\SpyQuoteTableMap;
use Orm\Zed\Quote\Persistence\SpyQuoteQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\SharedCartDataImport\Business\DataSet\SharedCartDataSetInterface;

class QuoteKeyToIdQuoteStep implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected $idQuoteCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $quoteKey = $dataSet[SharedCartDataSetInterface::KEY_QUOTE];
        if (!isset($this->idQuoteCache[$quoteKey])) {
            $quoteQuery = new SpyQuoteQuery();
            $idQuote = $quoteQuery
                ->select(SpyQuoteTableMap::COL_ID_QUOTE)
                ->findOneByKey($quoteKey);

            if (!$idQuote) {
                throw new EntityNotFoundException(sprintf('Could not find quote by key "%s"', $quoteKey));
            }

            $this->idQuoteCache[$quoteKey] = $idQuote;
        }

        $dataSet[SharedCartDataSetInterface::ID_QUOTE] = $this->idQuoteCache[$quoteKey];
    }
}
