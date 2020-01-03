<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CommentDataImport\Business\CommentDataImportStep;

use Orm\Zed\Comment\Persistence\SpyComment;
use Orm\Zed\Comment\Persistence\SpyCommentQuery;
use Orm\Zed\Comment\Persistence\SpyCommentTagQuery;
use Orm\Zed\Comment\Persistence\SpyCommentThread;
use Orm\Zed\Comment\Persistence\SpyCommentThreadQuery;
use Orm\Zed\Comment\Persistence\SpyCommentToCommentTagQuery;
use Spryker\Zed\CommentDataImport\Business\DataSet\CommentDataSetInterface;
use Spryker\Zed\CommentDataImport\Dependency\Service\CommentDataImportToUtilEncodingServiceInterface;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CommentWriterStep implements DataImportStepInterface
{
    /**
     * @var \Spryker\Zed\CommentDataImport\Dependency\Service\CommentDataImportToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\CommentDataImport\Dependency\Service\CommentDataImportToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(CommentDataImportToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->assertOwnerId($dataSet);

        $commentThreadEntity = $this->saveCommentThread($dataSet);

        $commentEntity = $this->createCommentQuery()
            ->filterByKey($dataSet[CommentDataSetInterface::COLUMN_MESSAGE_KEY])
            ->findOneOrCreate();

        $commentEntity
            ->setFkCommentThread($commentThreadEntity->getIdCommentThread())
            ->setFkCustomer($dataSet[CommentDataSetInterface::ID_CUSTOMER])
            ->setMessage($dataSet[CommentDataSetInterface::COLUMN_MESSAGE]);

        $commentEntity->save();

        $commentTagIds = $this->saveCommentTags($dataSet);

        if ($commentTagIds) {
            $this->saveCommentToCommentTags($commentTagIds, $commentEntity);
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    protected function assertOwnerId(DataSetInterface $dataSet): void
    {
        if (isset($dataSet[CommentDataSetInterface::COMMENT_THREAD_OWNER_ID])) {
            return;
        }

        throw new EntityNotFoundException(
            sprintf('Could not find owner id by owner key "%s"', $dataSet[CommentDataSetInterface::COLUMN_OWNER_KEY])
        );
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Orm\Zed\Comment\Persistence\SpyCommentThread
     */
    protected function saveCommentThread(DataSetInterface $dataSet): SpyCommentThread
    {
        $commentThreadEntity = $this->createCommentThreadQuery()
            ->filterByOwnerType($dataSet[CommentDataSetInterface::COLUMN_OWNER_TYPE])
            ->filterByOwnerId($dataSet[CommentDataSetInterface::COMMENT_THREAD_OWNER_ID])
            ->findOneOrCreate();

        $commentThreadEntity->save();

        return $commentThreadEntity;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return int[]
     */
    protected function saveCommentTags(DataSetInterface $dataSet): array
    {
        $commentTagIds = [];
        $decodedTags = $this->utilEncodingService->decodeJson($dataSet[CommentDataSetInterface::COLUMN_TAGS]);

        if (!$decodedTags) {
            return $commentTagIds;
        }

        foreach ($decodedTags as $tag) {
            $commentTagEntity = $this->createCommentTagQuery()
                ->filterByName($tag)
                ->findOneOrCreate();

            $commentTagEntity->save();
            $commentTagIds[] = $commentTagEntity->getIdCommentTag();
        }

        return $commentTagIds;
    }

    /**
     * @param array $commentTagIds
     * @param \Orm\Zed\Comment\Persistence\SpyComment $commentEntity
     *
     * @return void
     */
    protected function saveCommentToCommentTags(array $commentTagIds, SpyComment $commentEntity): void
    {
        foreach ($commentTagIds as $idCommentTag) {
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
