<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\FileManagerStorage\Business\FileManagerStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\FileManagerStorage\Persistence\FileManagerStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\FileManagerStorage\Persistence\FileManagerStorageRepositoryInterface getRepository()
 */
class FileManagerStorageFacade extends AbstractFacade implements FileManagerStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $fileIds
     *
     * @return void
     */
    public function publishFile(array $fileIds)
    {
        $this->getFactory()->createFileStorageWriter()->publish($fileIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $fileIds
     *
     * @return void
     */
    public function unpublishFile(array $fileIds)
    {
        $this->getFactory()->createFileStorageWriter()->unpublish($fileIds);
    }
}
