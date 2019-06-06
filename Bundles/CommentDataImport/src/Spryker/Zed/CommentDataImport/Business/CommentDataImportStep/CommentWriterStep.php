<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CommentDataImport\Business\CommentDataImportStep;

use Orm\Zed\Comment\Persistence\Base\SpyCommentTagQuery;
use Orm\Zed\Comment\Persistence\SpyComment;
use Orm\Zed\Comment\Persistence\SpyCommentQuery;
use Orm\Zed\Comment\Persistence\SpyCommentThreadQuery;
use Orm\Zed\Comment\Persistence\SpyCommentToCommentTagQuery;
use Spryker\Zed\CommentDataImport\Business\DataSet\CommentDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CommentWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        if (!isset($dataSet[CommentDataSetInterface::COMMENT_THREAD_OWNER_ID])) {
            throw new EntityNotFoundException(
                sprintf('Could not find owner id by owner key "%s"', $dataSet[CommentDataSetInterface::COLUMN_OWNER_KEY])
            );
        }

        $commentThreadEntity = $this->createCommentThreadQuery()
            ->filterByOwnerType($dataSet[CommentDataSetInterface::COLUMN_OWNER_TYPE])
            ->filterByOwnerId($dataSet[CommentDataSetInterface::COMMENT_THREAD_OWNER_ID])
            ->findOneOrCreate();

        $commentThreadEntity->save();

        $commentEntity = $this->createCommentQuery()
            ->filterByKey($dataSet[CommentDataSetInterface::COLUMN_MESSAGE_KEY])
            ->findOneOrCreate();

        $commentEntity
            ->setFkCommentThread($commentThreadEntity->getIdCommentThread())
            ->setFkCustomer($dataSet[CommentDataSetInterface::ID_CUSTOMER])
            ->setMessage($dataSet[CommentDataSetInterface::COLUMN_MESSAGE]);

        $commentEntity->save();

        $this->saveCommentTags($dataSet, $commentEntity);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param \Orm\Zed\Comment\Persistence\SpyComment $commentEntity
     *
     * @return void
     */
    protected function saveCommentTags(DataSetInterface $dataSet, SpyComment $commentEntity): void
    {
        $idCommentTags = [];
        $tags = json_decode($dataSet[CommentDataSetInterface::COLUMN_TAGS]);

        if (!$tags) {
            return;
        }

        foreach ($tags as $tag) {
            $commentTagEntity = $this->createCommentTagQuery()
                ->filterByName($tag)
                ->findOneOrCreate();

            $commentTagEntity->save();

            $idCommentTags[] = $commentTagEntity->getIdCommentTag();
        }

        foreach ($idCommentTags as $idCommentTag) {
            $commentToCommentTagEntity = $this->createCommentToCommentTagQuery()
                ->filterByFkComment($commentEntity->getIdComment())
                ->filterByFkCommentTag($idCommentTag)
                ->findOneOrCreate();

            $commentToCommentTagEntity->save();
        }
    }

    /**
     * @return \Orm\Zed\Comment\Persistence\SpyCommentThreadQuery
     */
    protected function createCommentThreadQuery(): SpyCommentThreadQuery
    {
        return SpyCommentThreadQuery::create();
    }

    /**
     * @return \Orm\Zed\Comment\Persistence\SpyCommentQuery
     */
    protected function createCommentQuery(): SpyCommentQuery
    {
        return SpyCommentQuery::create();
    }

    /**
     * @return \Orm\Zed\Comment\Persistence\SpyCommentTagQuery
     */
    protected function createCommentTagQuery(): SpyCommentTagQuery
    {
        return SpyCommentTagQuery::create();
    }

    /**
     * @return \Orm\Zed\Comment\Persistence\SpyCommentToCommentTagQuery
     */
    protected function createCommentToCommentTagQuery(): SpyCommentToCommentTagQuery
    {
        return SpyCommentToCommentTagQuery::create();
    }
}
