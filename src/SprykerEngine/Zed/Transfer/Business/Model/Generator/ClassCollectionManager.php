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
        $classDefinition = $this->getClassDefinition($data);

        if (isset($data['interface'])) {
            $classDefinition->setInterface($data['interface']);
        }

        if (isset($data['use'])) {
            $classDefinition->setUses($data['use']);
        }

        if (isset($data['property'][0])) {
            foreach ($data['property'] as $prop) {
                $classDefinition->setProperty($prop);
            }
        } else {
            $classDefinition->setProperty($data['property']);
        }
    }

    /**
     * @return array|ClassDefinition[]
     */
    public function getCollections()
    {
        return $this->definitionCollections;
    }

    /**
     * @param array $data
     *
     * @return mixed
     */
    private function getClassDefinition(array $data)
    {
        if (!isset($this->definitionCollections[$data['name']])) {
            $this->definitionCollections[$data['name']] = new ClassDefinition($data['name']);
        }

        return $this->definitionCollections[$data['name']];
    }
}
