<?php
/**
 *
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerEngine\Shared\Lumberjack\Model;

interface EventInterface
{

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
