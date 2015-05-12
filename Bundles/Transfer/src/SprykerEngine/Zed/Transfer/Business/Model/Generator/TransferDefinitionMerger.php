<?php

namespace SprykerEngine\Zed\Transfer\Business\Model\Generator;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Zend\Config\Factory;

class TransferDefinitionMerger
{

    /**
     * @var array
     */
    private $mergedTransferDefinitions = [];

    /**
     * @param array $transferDefinitions
     * @return array
     */
    public function merge(array $transferDefinitions)
    {
        $transferDefinitions = $this->normalizeDefinitions($transferDefinitions);
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
            'interface' => $this->mergeProperty($existingDefinition['interface'], $definitionToMerge['interface']),
            'property' => $this->mergeProperty($existingDefinition['property'], $definitionToMerge['property']),
        ];
    }

    /**
     * @param array $transferDefinitions
     *
     * @return array
     */
    private function normalizeDefinitions(array $transferDefinitions)
    {
        $normalizedDefinitions = [];
        foreach ($transferDefinitions as $transferDefinition) {

            $normalizedDefinition = [
                'name' => $transferDefinition['name'],
                'property' => $this->normalizeAttribute($transferDefinition['property']),
            ];

            if (array_key_exists('interface', $transferDefinition)) {
                $normalizedDefinition['interface'] = $this->normalizeAttribute($transferDefinition['interface']);
            }

            $normalizedDefinitions[] = $normalizedDefinition;
        }

        return $normalizedDefinitions;
    }

    /**
     * @param array $attribute
     *
     * @return array
     */
    private function normalizeAttribute(array $attribute)
    {
        if (isset($attribute[0])) {
            return $attribute;
        }

        return [$attribute];
    }

    /**
     * @param array $existingProperties
     * @param array $propertiesToMerge
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
                throw new \Exception('Property "' . $property['name'] . '" defined more than once with different attributes!');
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

        return count($diff) === 0;
    }

    /**
     * @param array $interfaces1
     * @param array $interfaces2
     *
     * @return array
     */
    private function mergeInterfaces(array $interfaces1, array $interfaces2)
    {
        $mergedInterfaces = [];

        foreach ($interfaces1 as $interface) {
            $mergedInterfaces[$interface['name']] = $interface;
        }

        foreach ($interfaces2 as $interface) {
            $mergedInterfaces[$interface['name']] = $interface;
        }

        return $mergedInterfaces;
    }
}
