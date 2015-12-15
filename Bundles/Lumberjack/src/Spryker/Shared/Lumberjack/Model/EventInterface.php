<?php

/**
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace Spryker\Shared\Lumberjack\Model;

interface EventInterface
{

    /**
     * @param string $name
     * @param array|string $data
     */
    public function addField($name, $data);

    /**
     * @param array $fields
     */
    public function addFields(array $fields);

    /**
     * @return array
     */
    public function getFields();

}
