<?php

namespace SprykerFeature\Zed\Transfer\Business\Model\Generator;

use SprykerFeature\Zed\Transfer\Business\Model\Generator\ClassDefinition;



class ClassCollectionManager
{
    protected $definitionCollections = [];

    public function setClassDefinition(array $data)
    {
        if ( ! isset($this->definitionCollections[$data['name']]) ) {
            $this->definitionCollections[$data['name']] = new ClassDefinition($data['name']);
        }

        $cd = $this->definitionCollections[$data['name']];

        if ( isset($data['interface']) ) {
            $cd->setInterface($data['interface']);
        }

        if ( isset($data['property'][0]) ) {
            foreach ($data['property'] as $prop) {
                $cd->setProperty($prop);
            }
        } else {
            $cd->setProperty($data['property']);
        }
    }

    public function getCollections()
    {
        return $this->definitionCollections;
    }
}
