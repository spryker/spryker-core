<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Transfer\Business\Model\Generator;

class TransferDefinitionMerger
{

    /**
     * @var array
     */
    private $mergedTransferDefinitions = [];

    /**
     * @param array $transferDefinitions
     *
     * @return array
     */
    public function merge(array $transferDefinitions)
    {
        foreach ($transferDefinitions as $transferDefinition) {
            if (array_key_exists($transferDefinition['name'], $this->mergedTransferDefinitions)) {
                $this->mergedTransferDefinitions[$transferDefinition['name']] = $this->mergeDefinitions(
                    $this->mergedTransferDefinitions[$transferDefinition['name']],
                    $transferDefinition
                );
            } else {
                $this->mergedTransferDefinitions[$transferDefinition['name']] = $transferDefinition;
            }
        }

        return $this->mergedTransferDefinitions;
    }

    /**
     * @param array $existingDefinition
     * @param array $definitionToMerge
     *
     * @return array
     */
    private function mergeDefinitions(array $existingDefinition, array $definitionToMerge)
    {
        return [
            'name' => $existingDefinition['name'],
            'interface' => $this->mergeInterfaces($existingDefinition, $definitionToMerge),
            'property' => $this->mergeProperty($existingDefinition['property'], $definitionToMerge['property']),
        ];
    }

    /**
     * @param array $existingProperties
     * @param array $propertiesToMerge
     *
     * @throws \Exception
     *
     * @return array
     */
    private function mergeProperty(array $existingProperties, array $propertiesToMerge)
    {
        $mergedProperties = [];

        foreach ($existingProperties as $property) {
            $mergedProperties[$property['name']] = $property;
        }

        foreach ($propertiesToMerge as $property) {
            if (!array_key_exists($property['name'], $mergedProperties)) {
                $mergedProperties[$property['name']] = $property;
            } elseif (!$this->propertiesAreIdentically($property, $mergedProperties[$property['name']])) {
                throw new \Exception('Property "' . $property['name'] . '" has two different definitions at "' . $mergedProperties[$property['name']]['bundle'] . '" and "' . $property['bundle'] . '" bundle');
            }
        }

        return $mergedProperties;
    }

    /**
     * @param $property1
     * @param $property2
     *
     * @return bool
     */
    private function propertiesAreIdentically(array $property1, array $property2)
    {
        $diff = array_diff($property1, $property2);
        if (count($diff) === 0) {
            return true;
        }

        if (count($diff) === 1 && isset($diff['bundle'])) {
            return true;
        }

        return false;
    }

    /**
     * @param array $definition1
     * @param array $definition2
     *
     * @return array
     */
    private function mergeInterfaces(array $definition1, array $definition2)
    {
        $mergedInterfaces = [];

        if (isset($definition1['interface'])) {
            foreach ($definition1['interface'] as $interface) {
                $mergedInterfaces[$interface['name']] = $interface;
            }
        }

        if (isset($definition2['interface'])) {
            foreach ($definition2['interface'] as $interface) {
                $mergedInterfaces[$interface['name']] = $interface;
            }
        }

        return $mergedInterfaces;
    }

}
