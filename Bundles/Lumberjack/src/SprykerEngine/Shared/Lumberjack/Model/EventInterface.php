<?php
/**
 *
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerEngine\Shared\Lumberjack\Model;

interface EventInterface
{


    const FIELD_EVENT_NAME = 'event_name';

    /**
     * @param $name
     * @param $data
     *
     * @return mixed
     */
    public function addField($name, $data);

    /**
     * @param array $fields
     *
     * @return mixed
     */
    public function addFields(array $fields);

    /**
     * @return mixed
     */
    public function getFields();
}
