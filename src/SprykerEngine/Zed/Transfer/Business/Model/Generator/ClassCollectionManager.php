<?php

namespace SprykerEngine\Zed\Transfer\Business\Model\Generator;

use SprykerEngine\Zed\Transfer\Business\Model\Generator\ClassDefinition;

/**
 * Class ClassCollectionManager
 * @package SprykerEngine\Zed\Transfer\Business\Model\Generator
 */
class ClassCollectionManager
{
    /**
     * @var array definitionCollections
     */
    protected $definitionCollections = [];

    /**
     * @param array $data
     */
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

    /**
     * @return array definitionCollections
     */
    public function getCollections()
    {
        return $this->definitionCollections;
    }
}
