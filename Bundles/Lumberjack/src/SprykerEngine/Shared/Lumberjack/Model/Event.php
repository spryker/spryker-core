<?php

/**
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerEngine\Shared\Lumberjack\Model;

class Event implements EventInterface
{

    const FIELD_NAME = 'name';

    const FIELD_EVENT_ID = '_event_id';

    /**
     * @var array
     */
    private $fields = [];

    public function __construct()
    {
        $this->addField(self::FIELD_NAME, null);
        $this->addField(self::FIELD_EVENT_ID, uniqid('', true));
    }

    /**
     * @param string $name
     * @param array|string $data
     *
     * @throws DataInvalidException
     */
    public function addField($name, $data)
    {
        if (!$this->isValidData($data)) {
            throw new DataInvalidException(sprintf(
                'Data contains invalid elements (maybe objects) for key %s',
                $name
            ));
        }

        $this->fields[$name] = $data;
    }

    /**
     * @param array|string $data
     *
     * @return bool
     */
    private function isValidData($data)
    {
        $check = !is_object($data);

        if (is_array($data)) {
            foreach ($data as $childElements) {
                $check |= $this->isValidData($childElements);
            }
        }

        return $check;
    }

    /**
     * @param array $fields
     *
     * @throws DataInvalidException
     */
    public function addFields(array $fields)
    {
        foreach ($fields as $name => $data) {
            $this->addField($name, $data);
        }
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

}
