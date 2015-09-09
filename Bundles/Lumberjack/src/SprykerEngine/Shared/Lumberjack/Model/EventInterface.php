<?php

/**
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerEngine\Shared\Lumberjack\Model;

interface EventInterface
{

    /**
     * @param string $name
     * @param array|string $data
     */
    public function addField($name, $data);

    /**
     * @param array $fields
     *
     * @return mixed
     */
    public function addFields(array $fields);

    /**
     * @return array
     */
    public function getFields();

}
