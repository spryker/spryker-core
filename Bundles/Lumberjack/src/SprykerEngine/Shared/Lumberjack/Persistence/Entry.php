<?php
/**
 *
 * (c) Copyright Spryker Systems GmbH 2015
 */
namespace SprykerFeature\Shared\Lumberjack\Persistence;

class EntryEntity implements EntryInterface
{

    /**
     * @var array
     */
    private $data = [];

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function addFields(array $data)
    {
        $this->data = array_merge($this->data, $data);
    }

    /**
     * @param $name
     * @param $value
     */
    public function addField($name, $value)
    {
        $this->data[$name] = $value;
    }
}
