<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentStorage\Communication\Plugin\Event;

use Generated\Shared\Transfer\FilterTransfer;
use Orm\Zed\Content\Persistence\Map\SpyContentTableMap;
use Spryker\Shared\ContentStorage\ContentStorageConfig;
use Spryker\Zed\Content\Dependency\ContentEvents;
use Spryker\Zed\EventBehavior\Dependency\Plugin\EventResourceBulkRepositoryPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ContentStorage\Business\ContentStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ContentStorage\Communication\ContentStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ContentStorage\Persistence\ContentStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ContentStorage\ContentStorageConfig getConfig()
 */
class ContentStorageEventResourceBulkRepositoryPlugin extends AbstractPlugin implements EventResourceBulkRepositoryPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return ContentStorageConfig::CONTENT_RESOURCE_NAME;
    }

    /**
     * {@inheritDoc}
     * - Returns ContentEntityTransfer collection by filter.
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\SpyContentEntityTransfer[]|\Spryker\Shared\Kernel\Transfer\AbstractEntityTransfer[]
     */
    public function getData(int $offset, int $limit): array
    {
        $filterTransfer = $this->createFilterTransfer($offset, $limit);

        return $this->getFacade()->getContentByFilter($filterTransfer);
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
        return ContentEvents::CONTENT_PUBLISH;
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
        return SpyContentTableMap::COL_ID_CONTENT;
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\FilterTransfer
     */
    protected function createFilterTransfer(int $offset, int $limit): FilterTransfer
    {
        return (new FilterTransfer())
            ->setOffset($offset)
            ->setLimit($limit);
    }
}
