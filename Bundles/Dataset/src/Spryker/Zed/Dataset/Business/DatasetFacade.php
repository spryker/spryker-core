<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Dataset\Business;

use Generated\Shared\Transfer\SpyDatasetEntityTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @method \Spryker\Zed\Dataset\Business\DatasetBusinessFactory getFactory()
 */
class DatasetFacade extends AbstractFacade implements DatasetFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idDataset
     *
     * @return bool
     */
    public function delete($idDataset)
    {
        return $this->getFactory()->createDatasetFinder()->delete($idDataset);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idDataset
     *
     * @return bool
     */
    public function activateById($idDataset)
    {
        return $this->getFactory()->createDatasetFinder()->activateById($idDataset);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idDataset
     *
     * @return bool
     */
    public function deactivateById($idDataset)
    {
        return $this->getFactory()->createDatasetFinder()->deactivateById($idDataset);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SpyDatasetEntityTransfer $saveRequestTransfer
     * @param null|\Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return bool
     */
    public function save(SpyDatasetEntityTransfer $saveRequestTransfer, UploadedFile $file = null)
    {
        return $this->getFactory()->createDatasetSaver()->save($saveRequestTransfer, $file);
    }
}
