<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerGui\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\FileManagerGui\Persistence\FileManagerGuiPersistenceFactory getFactory()
 */
class FileManagerGuiQueryContainer extends AbstractQueryContainer implements FileManagerGuiQueryContainerInterface
{
    /**
     * @api
     *
     * @return \Orm\Zed\FileManager\Persistence\SpyFileDirectoryQuery
     */
    public function queryFileDirectory()
    {
        return $this->getFactory()->createFileDirectoryQuery();
    }
}
