<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business\Model;

use Generated\Shared\Transfer\SpyDatasetRowEntityTransfer;
use Orm\Zed\Dataset\Persistence\SpyDatasetRow;

class DatasetRowSaver implements DatasetRowSaverInterface
{
    /**
     * @var \Spryker\Zed\Dataset\Business\Model\DatasetFinderInterface
     */
    protected $datasetFinder;

    /**
     * @param \Spryker\Zed\Dataset\Business\Model\DatasetFinderInterface $datasetFinder
     */
    public function __construct(DatasetFinderInterface $datasetFinder)
    {
        $this->datasetFinder = $datasetFinder;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyDatasetRowEntityTransfer $datasetRowEntityTransfer
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDataset
     */
    public function getOrCreate(SpyDatasetRowEntityTransfer $datasetRowEntityTransfer)
    {
        $datasetRowEntity = $this->datasetFinder->getDatasetRowByTitle($datasetRowEntityTransfer->getTitle());
        if ($datasetRowEntity === null) {
            $datasetRowEntity = new SpyDatasetRow();
            $datasetRowEntity->fromArray($datasetRowEntityTransfer->toArray());
        }
        $datasetRowEntity->save();

        return $datasetRowEntity;
    }
}
