<?php
/**
 *
 * (c) Copyright Spryker Systems GmbH 2015
 */
namespace SprykerFeature\Shared\Lumberjack\Persistence\Writer;
use SprykerFeature\Shared\Lumberjack\Persistence\EntryEntity;

interface WriterInterface {
    public function writeEntry(EntryEntity $entry);
}
