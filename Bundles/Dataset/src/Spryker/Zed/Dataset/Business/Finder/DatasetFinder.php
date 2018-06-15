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
     * @param string $name
     *
     * @return bool
     */
    public function existsDatasetByName($name): bool
    {
        return $this->repository->existsDatasetByName($name);
    }

    /**
     * @param int $idDataset
     *
     * @return \Generated\Shared\Transfer\DatasetTransfer
     */
    public function getDatasetModelById($idDataset): DatasetTransfer
    {
        return $this->repository->getDatasetByIdWithRelation($idDataset);
    }

    /**
     * @param string $datasetName
     *
     * @return \Generated\Shared\Transfer\DatasetTransfer
     */
    public function getDatasetModelByName($datasetName): DatasetTransfer
    {
        return $this->repository->getDatasetByNameWithRelation($datasetName);
    }
}
