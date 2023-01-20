<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerStorage\Communication\Plugin\Publisher;

use Generated\Shared\Transfer\FileCriteriaTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Spryker\Shared\FileManagerStorage\FileManagerStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherTriggerPluginInterface;

/**
 * @method \Spryker\Zed\FileManagerStorage\Communication\FileManagerStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\FileManagerStorage\Business\FileManagerStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\FileManagerStorage\FileManagerStorageConfig getConfig()
 */
class FileManagerPublisherTriggerPlugin extends AbstractPlugin implements PublisherTriggerPluginInterface
{
    /**
     * @uses \Orm\Zed\FileManager\Persistence\Map\SpyFileTableMap::COL_ID_FILE
     *
     * @var string
     */
    protected const COL_ID_FILE = 'spy_file.id_file';

    /**
     * {@inheritDoc}
     * - Retrieves collection of files by offset and limit from Persistence.
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     *
     * @return array<\Spryker\Shared\Kernel\Transfer\AbstractTransfer>
     */
    public function getData(int $offset, int $limit): array
    {
        $fileCriteriaTransfer = $this->createFileCriteriaTransfer($offset, $limit);

        return $this->getFactory()->getFileManagerFacade()
            ->getFileCollection($fileCriteriaTransfer)->getFiles()->getArrayCopy();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return FileManagerStorageConfig::FILE_RESOURCE_NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getEventName(): string
    {
        return FileManagerStorageConfig::FILE_PUBLISH;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string|null
     */
    public function getIdColumnName(): ?string
    {
        return static::COL_ID_FILE;
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\FileCriteriaTransfer
     */
    protected function createFileCriteriaTransfer(int $offset, int $limit): FileCriteriaTransfer
    {
        return (new FileCriteriaTransfer())
            ->setPagination(
                (new PaginationTransfer())->setLimit($limit)->setOffset($offset),
            );
    }
}
