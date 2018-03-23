<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\Publisher;

interface DataImporterPublisherInterface
{
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
