<?php

/**
 * (c) Copyright Spryker Systems GmbH 2015
 */
namespace Spryker\Shared\Lumberjack\Model\Writer;

use Spryker\Shared\Lumberjack\Model\EventInterface;

interface WriterInterface
{

    /**
     * @param \Spryker\Shared\Lumberjack\Model\EventInterface $event
     *
     * @return bool success or failure.
     */
    public function write(EventInterface $event);

    /**
     * @return string
     */
    public function getType();

}
