<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Comment\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\CommentCollectionTransfer;
use Generated\Shared\Transfer\CommentTransfer;
use Orm\Zed\Comment\Persistence\SpyComment;
use Propel\Runtime\Collection\Collection;

class CommentMapper
{
    /**
     * @param \Propel\Runtime\Collection\Collection $quoteRequestEntities
     *
     * @return \Generated\Shared\Transfer\CommentCollectionTransfer
     */
    public function mapEntityCollectionToTransferCollection(
        Collection $quoteRequestEntities
    ): CommentCollectionTransfer {
        $quoteRequestCollectionTransfer = new CommentCollectionTransfer();

        foreach ($quoteRequestEntities as $quoteRequestEntity) {
            $quoteRequestTransfer = $this->mapCommentEntityToCommentTransfer(
                $quoteRequestEntity,
                new CommentTransfer()
            );
            $quoteRequestCollectionTransfer->addComment($quoteRequestTransfer);
        }

        return $quoteRequestCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CommentTransfer $quoteRequestTransfer
     * @param \Orm\Zed\Comment\Persistence\SpyComment $quoteRequestEntity
     *
     * @return \Orm\Zed\Comment\Persistence\SpyComment
     */
    public function mapCommentTransferToCommentEntity(
        CommentTransfer $quoteRequestTransfer,
        SpyComment $quoteRequestEntity
    ): SpyComment {
        $quoteRequestEntity->fromArray($quoteRequestTransfer->modifiedToArray());
        $quoteRequestEntity->setFkCompanyUser($quoteRequestTransfer->getCompanyUser()->getIdCompanyUser());

        return $quoteRequestEntity;
    }

    /**
     * @param \Orm\Zed\Comment\Persistence\SpyComment $quoteRequestEntity
     * @param \Generated\Shared\Transfer\CommentTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CommentTransfer
     */
    public function mapCommentEntityToCommentTransfer(
        SpyComment $quoteRequestEntity,
        CommentTransfer $quoteRequestTransfer
    ): CommentTransfer {
        $quoteRequestTransfer = $quoteRequestTransfer->fromArray($quoteRequestEntity->toArray(), true);

        $quoteRequestTransfer->setCompanyUser($this->getCompanyUserTransfer($quoteRequestEntity));

        return $quoteRequestTransfer;
    }

    /**
     * @param \Orm\Zed\Comment\Persistence\SpyComment $quoteRequestEntity
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected function getCompanyUserTransfer(SpyComment $quoteRequestEntity): CompanyUserTransfer
    {
        $customerTransfer = (new CustomerTransfer())
            ->fromArray($quoteRequestEntity->getCompanyUser()->getCustomer()->toArray(), true);

        $companyTransfer = (new CompanyTransfer())
            ->fromArray($quoteRequestEntity->getCompanyUser()->getCompany()->toArray(), true);

        $companyUserTransfer = (new CompanyUserTransfer())
            ->fromArray($quoteRequestEntity->getCompanyUser()->toArray(), true);

        $companyUserTransfer
            ->setCustomer($customerTransfer)
            ->setCompany($companyTransfer);

        return $companyUserTransfer;
    }
}
