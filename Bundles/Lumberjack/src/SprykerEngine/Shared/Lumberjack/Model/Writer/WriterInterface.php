<?php
/**
 *
 * (c) Copyright Spryker Systems GmbH 2015
 */
namespace SprykerEngine\Shared\Lumberjack\Model\Writer;

use SprykerEngine\Shared\Lumberjack\Model\EventInterface;

interface WriterInterface
{

    /**
     * @param EventInterface $event
     *
     * @return bool success or failure.
     */
    public function write(EventInterface $event);

    public function setOptions(array $options);
}
