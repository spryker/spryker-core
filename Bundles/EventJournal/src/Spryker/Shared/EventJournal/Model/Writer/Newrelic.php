<?php

/**
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace Spryker\Shared\EventJournal\Model\Writer;

use Spryker\Shared\EventJournal\Model\EventInterface;
use Spryker\Shared\NewRelic\Api as NewRelicApi;

class Newrelic extends AbstractWriter
{

    const TYPE = 'newrelic';

    /**
     * @param EventInterface $event
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
