<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Communication\Plugin\Publisher;

use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Shared\GlossaryStorage\GlossaryStorageConfig;
use Spryker\Zed\Glossary\Dependency\GlossaryEvents;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\ResourcePublisherPluginInterface;

/**
 * @method \Spryker\Zed\GlossaryStorage\Business\GlossaryStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\GlossaryStorage\Communication\GlossaryStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\GlossaryStorage\GlossaryStorageConfig getConfig()
 * @method \Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageQueryContainerInterface getQueryContainer()
 */
class GlossaryResourcePublisherPlugin extends AbstractPlugin implements ResourcePublisherPluginInterface
{
    /**
     * @uses \Orm\Zed\Glossary\Persistence\Map\SpyGlossaryKeyTableMap::COL_ID_GLOSSARY_KEY
     */
    protected const COL_ID_GLOSSARY_KEY = 'spy_glossary_key.id_glossary_key';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return GlossaryStorageConfig::RESOURCE_NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\GlossaryKeyTransfer[]
     */
    public function getData(int $offset, int $limit): array
    {
        $filterTransfer = $this->createFilterTransfer($offset, $limit);

        return $this->getFacade()->findFilteredGlossaryKeyEntities($filterTransfer);
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
        return GlossaryEvents::GLOSSARY_KEY_PUBLISH;
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
        return static::COL_ID_GLOSSARY_KEY;
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
            ->setOrderBy($this->getIdColumnName())
            ->setOffset($offset)
            ->setLimit($limit);
    }
}
