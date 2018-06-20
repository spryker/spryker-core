<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\FileManagerStorage\Business\FileManagerStorageBusinessFactory getFactory()
 */
class FileManagerStorageFacade extends AbstractFacade implements FileManagerStorageFacadeInterface
{
    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function publish(array $fileIds)
    {
        $this->getFactory()->createFileStorageWriter()->publish($fileIds);
    }

    /**
     * @api
     *
     * {@inheritdoc}
     */
    public function unpublish(array $fileIds)
    {
        $this->getFactory()->createFileStorageWriter()->unpublish($fileIds);
    }
}
