<?php

/**
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerEngine\Shared\EventJournal\Model;

interface EventInterface
{

    /**
     * @param string $name
     * @param array|string $data
     */
    public function setField($name, $data);

    /**
     * @param array $fields
     */
    public function setFields(array $fields);

    /**
     * @return array
     */
    public function getFields();

}
