<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model;

interface DataImporterPublisherInterface
{
    /**
     * @return array
     */
    public static function getImportedEntityEvents();

    /**
     * @param array $importedEntityEvents
     *
     * @return void
     */
    public static function setImportedEntityEvents(array $importedEntityEvents);

    /**
     * @param array $events
     *
     * @return mixed
     */
    public static function addImportedEntityEvents(array $events);

    /**
     * @return void
     */
    public function triggerEvents();
}
