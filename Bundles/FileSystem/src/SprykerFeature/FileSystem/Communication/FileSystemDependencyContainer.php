<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\FileSystem\Communication;

use Generated\Zed\Ide\FactoryAutoCompletion\FileSystemCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\FileSystem\Persistence\FileSystemQueryContainerInterface;

/**
 * @method FileSystemCommunication getFactory()
 * @method FileSystemQueryContainerInterface getQueryContainer()
 */
class FileSystemDependencyContainer extends AbstractCommunicationDependencyContainer
{
}
