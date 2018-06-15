<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business\Finder;

use Generated\Shared\Transfer\DatasetTransfer;
use Spryker\Zed\Dataset\Persistence\DatasetEntityManagerInterface;
use Spryker\Zed\Dataset\Persistence\DatasetRepositoryInterface;

class DatasetFinder implements DatasetFinderInterface
{
    /**
     * @var \Spryker\Zed\Dataset\Persistence\DatasetRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\Dataset\Persistence\DatasetEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Spryker\Zed\Dataset\Persistence\DatasetRepositoryInterface $repository
     * @param \Spryker\Zed\Dataset\Persistence\DatasetEntityManagerInterface $entityManager
     */
    public function __construct(DatasetRepositoryInterface $repository, DatasetEntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     *
     * @return bool
     */
    public function existsDatasetByName(DatasetTransfer $datasetTransfer): bool
    {
        return $this->repository->existsDatasetByName($datasetTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     *
     * @return \Generated\Shared\Transfer\DatasetTransfer
     */
    public function getDatasetModelById(DatasetTransfer $datasetTransfer): DatasetTransfer
    {
        return $this->repository->getDatasetByIdWithRelation($datasetTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DatasetTransfer $datasetTransfer
     *
     * @return \Generated\Shared\Transfer\DatasetTransfer
     */
    public function getDatasetModelByName(DatasetTransfer $datasetTransfer): DatasetTransfer
    {
        return $this->repository->getDatasetByNameWithRelation($datasetTransfer);
    }
}
