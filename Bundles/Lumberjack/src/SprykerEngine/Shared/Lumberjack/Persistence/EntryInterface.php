<?php
/**
 *
 * (c) Copyright Spryker Systems GmbH 2015
 */
namespace SprykerFeature\Shared\Lumberjack\Persistence;

interface EntryInterface
{
    public function getFields();

    public function addFields(array $fields);

    public function addField($name, $value);
}
