<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Transfer\Business\Model\Generator;

class TransferDefinitionMerger implements MergerInterface
{

    const ERROR_MESSAGE_PROPERTIES_NOT_IDENTICALLY =
        'Property \'%1$s\' defined more than once with different attributes! To fix this, search for \'property name="%1$s"\' in the code base and fix the wrong one.';

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
            'bundles' => $this->mergeBundles($existingDefinition['bundles'], $definitionToMerge['bundles']),
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
                throw new \Exception(sprintf(self::ERROR_MESSAGE_PROPERTIES_NOT_IDENTICALLY, $property['name']));
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
     * @param array $bundles1
     * @param array $bundles2
     *
     * @return array
     */
    private function mergeBundles(array $bundles1, array $bundles2)
    {
        $mergedBundles = array_merge($bundles1, $bundles2);

        return array_unique($mergedBundles);
    }

}
