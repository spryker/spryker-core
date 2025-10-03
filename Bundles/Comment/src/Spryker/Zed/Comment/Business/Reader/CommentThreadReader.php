<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\CommentRequestTransfer;
use Generated\Shared\Transfer\CommentsRequestTransfer;
use Generated\Shared\Transfer\CommentThreadTransfer;
use Spryker\Zed\Comment\Persistence\CommentRepositoryInterface;

class CommentThreadReader implements CommentThreadReaderInterface
{
    /**
     * @var \Spryker\Zed\Comment\Persistence\CommentRepositoryInterface
     */
    protected $commentRepository;

    /**
     * @var list<\Spryker\Zed\CommentExtension\Dependency\Plugin\CommentExpanderPluginInterface>
     */
    protected array $commentExpanderPlugins;

    /**
     * @param \Spryker\Zed\Comment\Persistence\CommentRepositoryInterface $commentRepository
     * @param list<\Spryker\Zed\CommentExtension\Dependency\Plugin\CommentExpanderPluginInterface> $commentExpanderPlugins
     */
    public function __construct(CommentRepositoryInterface $commentRepository, array $commentExpanderPlugins)
    {
        $this->commentRepository = $commentRepository;
        $this->commentExpanderPlugins = $commentExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentRequestTransfer $commentRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer|null
     */
    public function findCommentThreadByOwner(CommentRequestTransfer $commentRequestTransfer): ?CommentThreadTransfer
    {
        $commentThreadTransfer = $this->commentRepository->findCommentThread($commentRequestTransfer);

        if ($commentThreadTransfer === null) {
            return null;
        }

        $commentTransfers = $this->commentRepository->findCommentsByCommentThread($commentThreadTransfer);
        $commentTransfers = $this->executeCommentExpanderPlugins($commentTransfers);
        $commentThreadTransfer->setComments(new ArrayObject($commentTransfers));

        return $commentThreadTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentsRequestTransfer $commentsRequestTransfer
     *
     * @return array<\Generated\Shared\Transfer\CommentThreadTransfer>
     */
    public function getCommentThreads(CommentsRequestTransfer $commentsRequestTransfer): array
    {
        $commentThreadTransfers = $this->commentRepository->getCommentThreads($commentsRequestTransfer);

        if ($commentThreadTransfers === []) {
            return [];
        }

        $threadIds = $this->collectThreadIds($commentThreadTransfers);
        $commentTransfers = $this->commentRepository->getCommentsByCommentThreadIds($threadIds);
        $commentTransfers = $this->executeCommentExpanderPlugins($commentTransfers);

        return $this->mapCommentsToThreads($commentThreadTransfers, $commentTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\CommentThreadTransfer $commentThreadTransfer
     *
     * @return \Generated\Shared\Transfer\CommentThreadTransfer|null
     */
    public function findCommentThreadById(CommentThreadTransfer $commentThreadTransfer): ?CommentThreadTransfer
    {
        $commentThreadTransfer = $this->commentRepository->findCommentThreadById($commentThreadTransfer);

        if ($commentThreadTransfer === null) {
            return null;
        }

        $commentTransfers = $this->commentRepository->findCommentsByCommentThread($commentThreadTransfer);
        $commentTransfers = $this->executeCommentExpanderPlugins($commentTransfers);
        $commentThreadTransfer->setComments(new ArrayObject($commentTransfers));

        return $commentThreadTransfer;
    }

    /**
     * @param array<\Generated\Shared\Transfer\CommentThreadTransfer> $commentThreadTransfers
     *
     * @return array<int>
     */
    protected function collectThreadIds(array $commentThreadTransfers): array
    {
        $threadIds = [];
        foreach ($commentThreadTransfers as $commentThreadTransfer) {
            $threadIds[] = $commentThreadTransfer->getIdCommentThread();
        }

        return $threadIds;
    }

    /**
     * @param array<\Generated\Shared\Transfer\CommentThreadTransfer> $commentThreadTransfers
     * @param array<\Generated\Shared\Transfer\CommentTransfer> $commentTransfers
     *
     * @return array<\Generated\Shared\Transfer\CommentThreadTransfer>
     */
    protected function mapCommentsToThreads(array $commentThreadTransfers, array $commentTransfers): array
    {
        $indexedCommentThreadTransfers = $this->getCommentThreadsIndexedById($commentThreadTransfers);

        foreach ($commentTransfers as $commentTransfer) {
            $idCommentThread = $commentTransfer->getIdCommentThread();
            if (!isset($indexedCommentThreadTransfers[$idCommentThread])) {
                continue;
            }

            $indexedCommentThreadTransfers[$idCommentThread]->addComment($commentTransfer);
        }

        return array_values($indexedCommentThreadTransfers);
    }

    /**
     * @param list<\Generated\Shared\Transfer\CommentTransfer> $commentTransfers
     *
     * @return list<\Generated\Shared\Transfer\CommentTransfer>
     */
    protected function executeCommentExpanderPlugins(array $commentTransfers): array
    {
        foreach ($this->commentExpanderPlugins as $commentExpanderPlugin) {
            $commentTransfers = $commentExpanderPlugin->expand($commentTransfers);
        }

        return $commentTransfers;
    }

    /**
     * @param list<\Generated\Shared\Transfer\CommentThreadTransfer> $commentThreadTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\CommentThreadTransfer>
     */
    protected function getCommentThreadsIndexedById(array $commentThreadTransfers): array
    {
        $commentThreadsIndexedById = [];
        foreach ($commentThreadTransfers as $commentThreadTransfer) {
            $commentThreadsIndexedById[$commentThreadTransfer->getIdCommentThreadOrFail()] = $commentThreadTransfer;
        }

        return $commentThreadsIndexedById;
    }
}
