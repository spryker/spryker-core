<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentStorage\Communication\Plugin\Event;

use Orm\Zed\Content\Persistence\Map\SpyContentTableMap;
use Spryker\Shared\ContentStorage\ContentStorageConstants;
use Spryker\Zed\Content\Dependency\ContentEvents;
use Spryker\Zed\EventBehavior\Dependency\Plugin\EventResourceRepositoryPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ContentStorage\Business\ContentStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ContentStorage\Communication\ContentStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ContentStorage\Persistence\ContentStorageRepositoryInterface getRepository()
 * @method \Spryker\Zed\ContentStorage\ContentStorageConfig getConfig()
 */
class ContentStorageEventResourceQueryContainerPlugin extends AbstractPlugin implements EventResourceRepositoryPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return ContentStorageConstants::CONTENT_RESOURCE_NAME;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\ContentTransfer[]
     */
    public function getData(array $ids = []): array
    {
        if (!empty($ids)) {
            return $contentEntities = $this->getRepository()->findContentByIds($ids)->getArrayCopy();
        }

        return $contentEntities = $this->getRepository()->findAllContent()->getArrayCopy();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getEventName(): string
    {
        return ContentEvents::ENTITY_SPY_CONTENT_PUBLISH;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string|null
     */
    public function getIdColumnName(): ?string
    {
        return SpyContentTableMap::COL_ID_CONTENT;
    }
}
