<?php

/**
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerEngine\Shared\Lumberjack\Model\Writer;

use SprykerEngine\Shared\Lumberjack\Model\EventInterface;
use SprykerFeature\Shared\NewRelic\Api as NewRelicApi;

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
