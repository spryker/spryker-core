<?php

namespace SprykerEngine\Zed\Transfer\Business\Model\Generator;

class ClassCollectionManager
{

    /**
     * @var array
     */
    protected $definitionCollections = [];

    /**
     * @param array $data
     */
    public function setClassDefinition(array $data)
    {
        if (!isset($this->definitionCollections[$data['name']])) {
            $this->definitionCollections[$data['name']] = new ClassDefinition($data);
        }
    }

    /**
     * @return array|ClassDefinition[]
     */
    public function getCollections()
    {
        return $this->definitionCollections;
    }
}
