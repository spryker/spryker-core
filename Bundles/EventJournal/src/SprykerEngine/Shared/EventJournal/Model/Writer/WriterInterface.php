<?php

/**
 * (c) Copyright Spryker Systems GmbH 2015
 */
namespace SprykerEngine\Shared\EventJournal\Model\Writer;

use SprykerEngine\Shared\EventJournal\Model\EventInterface;

interface WriterInterface
{

    /**
     * @param EventInterface $event
     *
     * @return bool success or failure.
     */
    public function write(EventInterface $event);

    /**
     * @return string
     */
    public function getType();

}
