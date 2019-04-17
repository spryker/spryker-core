<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\QuoteRequestDataImport\Business\QuoteRequestDataImportStep;

use Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestVersionQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\QuoteRequestDataImport\Business\DataSet\QuoteRequestVersionDataSetInterface;

class QuoteRequestVersionWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $quoteRequestVersionEntity = $this->createQuoteRequestVersionQuery()
            ->filterByFkQuoteRequest($dataSet[QuoteRequestVersionDataSetInterface::ID_QUOTE_REQUEST])
            ->filterByVersion((int)$dataSet[QuoteRequestVersionDataSetInterface::COLUMN_VERSION])
            ->findOneOrCreate();

        $quoteRequestVersionEntity
            ->setVersionReference($dataSet[QuoteRequestVersionDataSetInterface::COLUMN_VERSION_REFERENCE])
            ->setMetadata($dataSet[QuoteRequestVersionDataSetInterface::COLUMN_METADATA])
            ->setQuote($dataSet[QuoteRequestVersionDataSetInterface::COLUMN_QUOTE])
            ->save();
    }

    /**
     * @return \Orm\Zed\QuoteRequest\Persistence\SpyQuoteRequestVersionQuery
     */
    protected function createQuoteRequestVersionQuery(): SpyQuoteRequestVersionQuery
    {
        return SpyQuoteRequestVersionQuery::create();
    }
}
