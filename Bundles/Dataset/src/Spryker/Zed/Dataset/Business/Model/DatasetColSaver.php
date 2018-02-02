<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business\Model;

use Generated\Shared\Transfer\SpyDatasetColEntityTransfer;
use Orm\Zed\Dataset\Persistence\SpyDatasetCol;

class DatasetColSaver implements DatasetColSaverInterface
{
    /**
     * @return \Spryker\Zed\Dataset\Business\Model\DatasetFinderInterface
     */
    protected $datasetFinder;

    /**
     * DatasetColSaver constructor.
     * @param \Spryker\Zed\Dataset\Business\Model\DatasetFinderInterface $datasetFinder
     */
    public function __construct(DatasetFinderInterface $datasetFinder)
    {
        $this->datasetFinder = $datasetFinder;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyDatasetColEntityTransfer $datasetColEntityTransfer
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDataset
     */
    public function getOrCreate(SpyDatasetColEntityTransfer $datasetColEntityTransfer)
    {
        $datasetColEntity = $this->datasetFinder->getDatasetColByTitle($datasetColEntityTransfer->getTitle());
        if ($datasetColEntity === null) {
            $datasetColEntity = new SpyDatasetCol();
            $datasetColEntity->fromArray($datasetColEntityTransfer->toArray());
        }
        $datasetColEntity->save();

        return $datasetColEntity;
    }
}
