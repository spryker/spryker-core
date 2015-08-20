<?php
/**
 *
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerEngine\Shared\Lumberjack\Model;

interface EventInterface
{

    const FIELD_EVENT_NAME = 'event_name';

    public function addField($name, $data);

    public function addFields(array $fields);

    public function getFields();
}
