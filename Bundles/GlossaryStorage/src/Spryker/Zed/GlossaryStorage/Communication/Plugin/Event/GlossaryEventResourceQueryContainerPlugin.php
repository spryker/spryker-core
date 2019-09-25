<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Communication\Plugin\Event;

use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryKeyTableMap;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Shared\GlossaryStorage\GlossaryStorageConstants;
use Spryker\Zed\EventBehavior\Dependency\Plugin\EventResourceQueryContainerPluginInterface;
use Spryker\Zed\Glossary\Dependency\GlossaryEvents;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\GlossaryStorage\Business\GlossaryStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\GlossaryStorage\Communication\GlossaryStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\GlossaryStorage\GlossaryStorageConfig getConfig()
 */
class GlossaryEventResourceQueryContainerPlugin extends AbstractPlugin implements EventResourceQueryContainerPluginInterface
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
        return GlossaryStorageConstants::RESOURCE_NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $ids
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria|null
     */
    public function queryData(array $ids = []): ?ModelCriteria
    {
        $query = $this->getQueryContainer()->queryGlossaryKeysByIds($ids);

        if ($ids === []) {
            $query->clear();
        }

        return $query->orderBy($this->getIdColumnName());
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
        return SpyGlossaryKeyTableMap::COL_ID_GLOSSARY_KEY;
    }
}
