<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CommentDataImport\Business\CommentDataImportStep;

use Orm\Zed\Quote\Persistence\Map\SpyQuoteTableMap;
use Orm\Zed\Quote\Persistence\SpyQuoteQuery;
use Spryker\Zed\CommentDataImport\Business\DataSet\CommentDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class QuoteOwnerKeyToCommentThreadOwnerIdStep implements DataImportStepInterface
{
    protected const QUOTE_TYPE = 'quote';

    /**
     * @var int[]
     */
    protected $idQuoteCache;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        if ($dataSet[CommentDataSetInterface::COLUMN_OWNER_TYPE] !== static::QUOTE_TYPE) {
            return;
        }

        $ownerKey = $dataSet[CommentDataSetInterface::COLUMN_OWNER_KEY];

        if (!isset($this->idQuoteCache[$ownerKey])) {
            $idQuote = $this->createQuoteQuery()
                ->select([SpyQuoteTableMap::COL_ID_QUOTE])
                ->findOneByKey($ownerKey);

            if (!$idQuote) {
                throw new EntityNotFoundException(sprintf('Could not find quote by key "%s"', $ownerKey));
            }

            $this->idQuoteCache[$ownerKey] = $idQuote;
        }

        $dataSet[CommentDataSetInterface::COMMENT_THREAD_OWNER_ID] = $this->idQuoteCache[$ownerKey];
    }

    /**
     * @return \Orm\Zed\Quote\Persistence\SpyQuoteQuery
     */
    protected function createQuoteQuery(): SpyQuoteQuery
    {
        return SpyQuoteQuery::create();
    }
}
