<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManager\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\FileManager\Business\FileManagerFacadeInterface getFacade()
 * @method \Spryker\Zed\FileManager\FileManagerConfig getConfig()
 * @method \Spryker\Zed\FileManager\Persistence\FileManagerQueryContainerInterface getQueryContainer()
 */
class FileManagerCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\FileManager\Business\FileManagerFacade
     */
    public function getFileManagerFacade()
    {
        return $this->getFacade();
    }
}
