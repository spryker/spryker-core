<?php
/**
 *
 * (c) Copyright Spryker Systems GmbH 2015
 */

namespace SprykerEngine\Shared\Lumberjack\Model;

class Event implements EventInterface
{

    private $fields = [];

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

    public function addFields(array $fields)
    {
        foreach ($fields as $name => $data) {
            $this->addField($name, $data);
        }
    }

    public function getFields() {
        return $this->fields;
    }

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
}
