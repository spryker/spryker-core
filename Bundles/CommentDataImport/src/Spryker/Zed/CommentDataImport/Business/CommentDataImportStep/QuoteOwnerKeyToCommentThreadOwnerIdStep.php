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
    protected const OWNER_TYPE = 'quote';

    /**
     * @var int[]
     */
    protected $idQuoteBuffer;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        if ($dataSet[CommentDataSetInterface::COLUMN_OWNER_TYPE] !== static::OWNER_TYPE) {
            return;
        }

        $quoteKey = $dataSet[CommentDataSetInterface::COLUMN_OWNER_KEY];

        $dataSet[CommentDataSetInterface::COMMENT_THREAD_OWNER_ID] = $this->getIdQuoteByQuoteKey($quoteKey);
    }

    /**
     * @param string $quoteKey
     *
     * @return int
     */
    protected function getIdQuoteByQuoteKey(string $quoteKey): int
    {
        if (isset($this->idQuoteBuffer[$quoteKey])) {
            return $this->idQuoteBuffer[$quoteKey];
        }

        return $this->resolveOwnerKey($quoteKey);
    }

    /**
     * @param string $quoteKey
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function resolveOwnerKey(string $quoteKey): int
    {
        /** @var int $idQuote */
        $idQuote = $this->createQuoteQuery()
            ->select([SpyQuoteTableMap::COL_ID_QUOTE])
            ->findOneByKey($quoteKey);

        if (!$idQuote) {
            throw new EntityNotFoundException(sprintf('Could not find quote by key "%s"', $quoteKey));
        }

        $this->idQuoteBuffer[$quoteKey] = $idQuote;

        return $idQuote;
    }

    /**
     * @return \Orm\Zed\Quote\Persistence\SpyQuoteQuery
     */
    protected function createQuoteQuery(): SpyQuoteQuery
    {
        return SpyQuoteQuery::create();
    }
}
