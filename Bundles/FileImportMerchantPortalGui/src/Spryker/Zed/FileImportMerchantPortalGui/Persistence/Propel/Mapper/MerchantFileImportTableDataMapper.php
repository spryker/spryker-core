<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\FileImportMerchantPortalGui\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\MerchantFileImportCollectionTransfer;
use Generated\Shared\Transfer\MerchantFileImportTransfer;
use Generated\Shared\Transfer\MerchantFileTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Orm\Zed\FileImportMerchantPortalGui\Persistence\SpyMerchantFileImport;
use Orm\Zed\MerchantFile\Persistence\SpyMerchantFile;
use Orm\Zed\User\Persistence\SpyUser;
use Propel\Runtime\Util\PropelModelPager;

class MerchantFileImportTableDataMapper implements MerchantFileImportTableDataMapperInterface
{
    /**
     * @param array<\Orm\Zed\FileImportMerchantPortalGui\Persistence\SpyMerchantFileImport> $merchantFileImportEntities
     * @param \Generated\Shared\Transfer\MerchantFileImportCollectionTransfer $merchantFileImportCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileImportCollectionTransfer
     */
    public function mapMerchantFileImportEntityArrayToCollectionTransfer(
        array $merchantFileImportEntities,
        MerchantFileImportCollectionTransfer $merchantFileImportCollectionTransfer
    ): MerchantFileImportCollectionTransfer {
        foreach ($merchantFileImportEntities as $merchantFileImportEntity) {
            $merchantFileImportTransfer = $this->mapMerchantFileImportEntityToTransfer(
                $merchantFileImportEntity,
                new MerchantFileImportTransfer(),
            );

            $merchantFileImportCollectionTransfer->addMerchantFileImport($merchantFileImportTransfer);
        }

        return $merchantFileImportCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\FileImportMerchantPortalGui\Persistence\SpyMerchantFileImport $merchantFileImportEntity
     * @param \Generated\Shared\Transfer\MerchantFileImportTransfer $merchantFileImportTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileImportTransfer
     */
    public function mapMerchantFileImportEntityToTransfer(
        SpyMerchantFileImport $merchantFileImportEntity,
        MerchantFileImportTransfer $merchantFileImportTransfer
    ): MerchantFileImportTransfer {
        $merchantFileImportTransfer = $merchantFileImportTransfer->fromArray($merchantFileImportEntity->toArray(), true);

        $merchantFileTransfer = $this->mapMerchantFileEntityToTransfer(
            $merchantFileImportEntity->getSpyMerchantFile(),
            new MerchantFileTransfer(),
        );

        return $merchantFileImportTransfer->setMerchantFile($merchantFileTransfer);
    }

    /**
     * @param \Orm\Zed\MerchantFile\Persistence\SpyMerchantFile $merchantFileEntity
     * @param \Generated\Shared\Transfer\MerchantFileTransfer $merchantFileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantFileTransfer
     */
    protected function mapMerchantFileEntityToTransfer(
        SpyMerchantFile $merchantFileEntity,
        MerchantFileTransfer $merchantFileTransfer
    ): MerchantFileTransfer {
        $merchantFileTransfer = $merchantFileTransfer->fromArray($merchantFileEntity->toArray(), true);

        $userTransfer = $this->mapUserEntityToTransfer(
            $merchantFileEntity->getUser(),
            new UserTransfer(),
        );

        return $merchantFileTransfer->setUser($userTransfer);
    }

    /**
     * @param \Propel\Runtime\Util\PropelModelPager $propelModelPager
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Generated\Shared\Transfer\PaginationTransfer
     */
    public function mapPropelModelPagerToPaginationTransfer(
        PropelModelPager $propelModelPager,
        PaginationTransfer $paginationTransfer
    ): PaginationTransfer {
        return $paginationTransfer
            ->setPage($propelModelPager->getPage())
            ->setMaxPerPage($propelModelPager->getMaxPerPage())
            ->setNbResults($propelModelPager->getNbResults())
            ->setFirstIndex($propelModelPager->getFirstIndex())
            ->setLastIndex($propelModelPager->getLastIndex())
            ->setFirstPage($propelModelPager->getFirstPage())
            ->setLastPage($propelModelPager->getLastPage())
            ->setNextPage($propelModelPager->getNextPage())
            ->setPreviousPage($propelModelPager->getPreviousPage());
    }

    /**
     * @param \Orm\Zed\User\Persistence\SpyUser $userEntity
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function mapUserEntityToTransfer(SpyUser $userEntity, UserTransfer $userTransfer): UserTransfer
    {
        return $userTransfer->fromArray($userEntity->toArray(), true);
    }
}
