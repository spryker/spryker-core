<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business\Model;

use Spryker\Shared\Dataset\DatasetConstants;
use Spryker\Zed\Dataset\Business\Exception\DatasetNotFoundException;
use Spryker\Zed\Dataset\Dependency\Facade\DatasetToTouchFacadeInterface;
use Spryker\Zed\Dataset\Persistence\DatasetQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class DatasetFinder implements DatasetFinderInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\Dataset\Persistence\DatasetQueryContainerInterface
     */
    protected $datasetQueryContainer;

    /**
     * @var \Spryker\Zed\Dataset\Dependency\Facade\DatasetToTouchFacadeInterface
     */
    protected $touchFacade;

    /**
     * @param \Spryker\Zed\Dataset\Persistence\DatasetQueryContainerInterface $datasetQueryContainer
     * @param \Spryker\Zed\Dataset\Dependency\Facade\DatasetToTouchFacadeInterface $touchFacade
     */
    public function __construct(
        DatasetQueryContainerInterface $datasetQueryContainer,
        DatasetToTouchFacadeInterface $touchFacade
    ) {
        $this->datasetQueryContainer = $datasetQueryContainer;
        $this->touchFacade = $touchFacade;
    }

    /**
     * @param int $idDataset
     *
     * @return bool
     */
    public function delete($idDataset)
    {
        $this->datasetQueryContainer->queryDatasetById($idDataset)->delete();

        return true;
    }

    /**
     * @param int $idDataset
     *
     * @return bool
     */
    public function activateById($idDataset)
    {
        $this->handleDatabaseTransaction(function () use ($idDataset) {
            $this->updateIsActiveByIdTransaction($idDataset, true);
            $this->touchFacade->touchActive(DatasetConstants::RESOURCE_TYPE_DATASET, $idDataset);
        });

        return true;
    }

    /**
     * @param int $idDataset
     *
     * @return bool
     */
    public function deactivateById($idDataset)
    {
        $this->handleDatabaseTransaction(function () use ($idDataset) {
            $this->updateIsActiveByIdTransaction($idDataset, false);
            $this->touchFacade->touchDeleted(DatasetConstants::RESOURCE_TYPE_DATASET, $idDataset);
        });

        return true;
    }

    /**
     * @param int $idDataset
     * @param bool $isActive
     *
     * @return void
     */
    protected function updateIsActiveByIdTransaction($idDataset, $isActive)
    {
        $spyDataset = $this->getDatasetyId($idDataset);
        $spyDataset->setIsActive($isActive);
        $spyDataset->save();
    }

    /**
     * @param int $idDataset
     *
     * @throws \Spryker\Zed\Dataset\Business\Exception\DatasetNotFoundException
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDataset
     */
    public function getDatasetyId($idDataset)
    {
        $spyDataset = $this->datasetQueryContainer->queryDatasetById($idDataset)->findOne();

        if (!$spyDataset) {
            throw new DatasetNotFoundException();
        }

        return $spyDataset;
    }

    /**
     * @param string $title
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDataset
     */
    public function getDatasetRowByTitle($title)
    {
        return $this->datasetQueryContainer->queryDatasetRowByTitle($title)->findOne();
    }

    /**
     * @param string $title
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDataset
     */
    public function getDatasetColByTitle($title)
    {
        return $this->datasetQueryContainer->queryDatasetColByTitle($title)->findOne();
    }
}
