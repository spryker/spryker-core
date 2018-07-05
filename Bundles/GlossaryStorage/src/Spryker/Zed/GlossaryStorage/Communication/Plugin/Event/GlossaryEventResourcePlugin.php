<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Communication\Plugin\Event;

use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryKeyTableMap;
use Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery;
use Spryker\Shared\GlossaryStorage\GlossaryStorageConstants;
use Spryker\Zed\EventBehavior\Dependency\Plugin\EventResourceQueryContainerPluginInterface;
use Spryker\Zed\Glossary\Dependency\GlossaryEvents;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\GlossaryStorage\Persistence\GlossaryStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\GlossaryStorage\Business\GlossaryStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\GlossaryStorage\Communication\GlossaryStorageCommunicationFactory getFactory()
 */
class GlossaryEventResourcePlugin extends AbstractPlugin implements EventResourceQueryContainerPluginInterface
{
    /**
     * Specification:
     *  - Returns the name of resource
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
     * Specification:
     *  - Returns query of resource entity, provided $ids parameter
     *    will apply to query to limit the result
     *
     * @api
     *
     * @param int[] $ids
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery
     */
    public function queryData(array $ids = []): SpyGlossaryKeyQuery
    {
        $query = $this->getQueryContainer()->queryGlossaryKeysByIds($ids);

        if (empty($ids)) {
            $query->clear();
        }

        return $query;
    }

    /**
     * Specification:
     *  - Returns the event name of resource entity
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
     * Specification:
     *  - Returns the name of ID column for publishing
     *
     * @api
     *
     * @return string
     */
    public function getIdColumnName(): string
    {
        return SpyGlossaryKeyTableMap::COL_ID_GLOSSARY_KEY;
    }
}
