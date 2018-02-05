<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business\Model;

interface DatasetFinderInterface
{
    /**
     * @param int $idDataset
     *
     * @return bool
     */
    public function delete($idDataset);

    /**
     * @param int $idDataset
     *
     * @return bool
     */
    public function activateById($idDataset);

    /**
     * @param int $idDataset
     *
     * @return bool
     */
    public function deactivateById($idDataset);

    /**
     * @param int $idDataset
     *
     * @throws \Spryker\Zed\Dataset\Business\Exception\DatasetNotFoundException
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDataset
     */
    public function getDatasetyId($idDataset);

    /**
     * @param string $title
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDataset
     */
    public function getDatasetRowByTitle($title);

    /**
     * @param string $title
     *
     * @return \Orm\Zed\Dataset\Persistence\SpyDataset
     */
    public function getDatasetColByTitle($title);
}
