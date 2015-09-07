<?php
/**
 *
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerEngine\Shared\Lumberjack\Model;

class Event implements EventInterface
{

    /**
     * @var array
     */
    private $fields = [];

    public function __construct()
    {
        $this->addField('name', null);
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
                "Data contains invalid elements (maybe objects) for key %s",
                $name
            ));
        }

        $this->fields[$name] = $data;
    }

    /**
     * @param $data
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
