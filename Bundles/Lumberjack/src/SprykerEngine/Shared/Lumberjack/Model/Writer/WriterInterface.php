<?php
/**
 *
 * (c) Copyright Spryker Systems GmbH 2015
 */
namespace SprykerEngine\Shared\Lumberjack\Model\Writer;

use SprykerEngine\Shared\Lumberjack\Model\EventInterface;

interface WriterInterface
{

    public function write(EventInterface $entry);
}
