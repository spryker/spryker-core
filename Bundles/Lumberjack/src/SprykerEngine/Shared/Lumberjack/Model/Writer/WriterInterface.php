<?php
/**
 *
 * (c) Copyright Spryker Systems GmbH 2015
 */
namespace SprykerEngine\Zed\Lumberjack\Persistence\Writer;
use SprykerEngine\Zed\Lumberjack\Persistence\EntryEntity;

interface WriterInterface {
    public function writeEntry(EntryEntity $entry);
}
