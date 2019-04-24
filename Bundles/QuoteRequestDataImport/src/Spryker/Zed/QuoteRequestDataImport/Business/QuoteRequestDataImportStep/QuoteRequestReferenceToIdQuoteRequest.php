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

class QuoteRequestReferenceToIdQuoteRequest implements DataImportStepInterface
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
        $quoteRequestReference = $dataSet[QuoteRequestVersionDataSetInterface::COLUMN_QUOTE_REQUEST_REFERENCE];

        if (!isset($this->idQuoteRequestCache[$quoteRequestReference])) {
            $idQuoteRequest = $this->createQuoteRequestQuery()
                ->select([SpyQuoteRequestTableMap::COL_ID_QUOTE_REQUEST])
                ->findOneByQuoteRequestReference($quoteRequestReference);

            if (!$idQuoteRequest) {
                throw new EntityNotFoundException(sprintf('Could not find quote request by reference "%s"', $quoteRequestReference));
            }

            $this->idQuoteRequestCache[$quoteRequestReference] = $idQuoteRequest;
        }

        $dataSet[QuoteRequestVersionDataSetInterface::ID_QUOTE_REQUEST] = $this->idQuoteRequestCache[$quoteRequestReference];
    }

    /**
     * @return \Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestQuery
     */
    protected function createQuoteRequestQuery(): SpyQuoteRequestQuery
    {
        return SpyQuoteRequestQuery::create();
    }
}
