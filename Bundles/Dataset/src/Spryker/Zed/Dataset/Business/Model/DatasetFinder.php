<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business\Model;

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
     * @param int $idDataset
     *
     * @return void
     */
    public function delete($idDataset)
    {
        $this->entityManager->delete($idDataset);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasDatasetName($name)
    {
        return $this->repository->hasDatasetName($name);
    }

    /**
     * @param int $idDataset
     *
     * @return void
     */
    public function activateById($idDataset)
    {
        $this->entityManager->updateIsActiveByIdDataset($idDataset, true);
    }

    /**
     * @param int $idDataset
     *
     * @return void
     */
    public function deactivateById($idDataset)
    {
        $this->entityManager->updateIsActiveByIdDataset($idDataset, false);
    }

    /**
     * @param int $idDataset
     *
     * @return \Generated\Shared\Transfer\SpyDatasetEntityTransfer
     */
    public function getDatasetModelById($idDataset)
    {
        return $this->repository->getDatasetByIdWithRelation($idDataset);
    }

    /**
     * @param string $datasetName
     *
     * @return \Generated\Shared\Transfer\SpyDatasetEntityTransfer
     */
    public function getDatasetModelByName($datasetName)
    {
        return $this->repository->getDatasetByNameWithRelation($datasetName);
    }
}
