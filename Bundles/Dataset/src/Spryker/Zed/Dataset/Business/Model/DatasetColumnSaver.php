<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business\Model;

use Generated\Shared\Transfer\SpyDatasetColumnEntityTransfer;
use Orm\Zed\Dataset\Persistence\SpyDatasetColumn;

class DatasetColumnSaver implements DatasetColumnSaverInterface
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
     * @param \Generated\Shared\Transfer\SpyDatasetColumnEntityTransfer $datasetColumnEntityTransfer
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDatasetColumn
     */
    public function findOrCreate(SpyDatasetColumnEntityTransfer $datasetColumnEntityTransfer)
    {
        $datasetColumnEntity = $this->datasetFinder->getDatasetColumnByTitle($datasetColumnEntityTransfer->getTitle());
        if ($datasetColumnEntity === null) {
            $datasetColumnEntity = new SpyDatasetColumn();
            $datasetColumnEntity->fromArray($datasetColumnEntityTransfer->toArray());
        }
        $datasetColumnEntity->save();

        return $datasetColumnEntity;
    }
}
