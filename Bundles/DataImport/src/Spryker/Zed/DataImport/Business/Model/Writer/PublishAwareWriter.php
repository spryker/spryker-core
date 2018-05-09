<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\Writer;

use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;

/**
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 */
class PublishAwareWriter extends DataImporterPublisher
{
    /**
     * @var array
     */
    protected $entityEvents = [];

    /**
     * //TODO refactor this, maybe we don't need this class!!!
     * @param string $eventName
     * @param int $entityId
     *
     * @return void
     */
    public function addPublishEvents($eventName, $entityId)
    {
        $this->entityEvents[$eventName][] = $entityId;

        DataImporterPublisher::addImportedEntityEvents($this->entityEvents);
    }
}
