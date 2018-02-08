<?php

namespace Spryker\Zed\FileManager\Communication;

use Spryker\Zed\FileManager\Business\FileManagerFacade;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method FileManagerFacade getFacade()
 */
class FileManagerCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return FileManagerFacade
     */
    public function getFileManagerFacade()
    {
        return $this->getFacade();
    }

}