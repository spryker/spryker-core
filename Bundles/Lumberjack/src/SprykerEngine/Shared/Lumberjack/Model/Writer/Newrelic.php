<?php
/**
 *
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerEngine\Shared\Lumberjack\Model\Writer;

use SprykerEngine\Shared\Lumberjack\Model\EventInterface;

class Newrelic extends AbstractWriter
{

    public function write(EventInterface $event)
    {
        $api = \SprykerFeature\Shared\Library\NewRelic\Api()::getInstance();

        //foreach($event)
        return true;
    }
}
