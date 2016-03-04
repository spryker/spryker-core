<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\EventJournal\Model\Writer;

use Spryker\Shared\EventJournal\Model\EventInterface;
use Spryker\Shared\NewRelic\Api as NewRelicApi;

class Newrelic extends AbstractWriter
{

    const TYPE = 'newrelic';

    /**
     * @param \Spryker\Shared\EventJournal\Model\EventInterface $event
     *
     * @return bool
     */
    public function write(EventInterface $event)
    {
        $api = new NewRelicApi();

        foreach ($event as $field => $value) {
            $api->addCustomParameter($field, $value);
        }

        return true;
    }

}
