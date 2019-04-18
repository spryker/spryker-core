<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\QuoteRequestDataImport\Business\QuoteRequestDataImportStep;

use Orm\Zed\QuoteRequest\Persistence\Map\SpyQuoteRequestTableMap;
use Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\QuoteRequestDataImport\Business\DataSet\QuoteRequestVersionDataSetInterface;

class QuoteRequestKeyToIdQuoteRequest implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected $idQuoteRequestCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $quoteRequestKey = $dataSet[QuoteRequestVersionDataSetInterface::COLUMN_QUOTE_REQUEST_KEY];

        if (!isset($this->idQuoteRequestCache[$quoteRequestKey])) {
            $idQuoteRequest = $this->createQuoteRequestQuery()
                ->select([SpyQuoteRequestTableMap::COL_ID_QUOTE_REQUEST])
                ->findOneByKey($quoteRequestKey);

            if (!$idQuoteRequest) {
                throw new EntityNotFoundException(sprintf('Could not find quote request by key "%s"', $quoteRequestKey));
            }

            $this->idQuoteRequestCache[$quoteRequestKey] = $idQuoteRequest;
        }

        $dataSet[QuoteRequestVersionDataSetInterface::ID_QUOTE_REQUEST] = $this->idQuoteRequestCache[$quoteRequestKey];
    }

    /**
     * @return \Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestQuery
     */
    protected function createQuoteRequestQuery(): SpyQuoteRequestQuery
    {
        return SpyQuoteRequestQuery::create();
    }
}
