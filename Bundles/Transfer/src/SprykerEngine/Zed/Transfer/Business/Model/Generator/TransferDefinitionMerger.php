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
            } else {
                $mergedProperties[$property['name']] = $this->mergePropertyBundles(
                    $mergedProperties[$property['name']],
                    $property
                );
            }
        }

        return $mergedProperties;
    }

    /**
     * @param array $property1
     * @param array $property2
     *
     * @return bool
     */
    private function propertiesAreIdentically(array $property1, array $property2)
    {
        unset($property1['bundle'], $property1['bundles']);
        unset($property2['bundle'], $property2['bundles']);

        $diff = array_diff($property1, $property2);
        if (count($diff) === 0) {
            return true;
        }

        return false;
    }

    /**
     * @param array $property1
     * @param array $property2
     *
     * @return array
     */
    private function mergePropertyBundles(array $property1, array $property2)
    {
        $mergedPropertyBundles = array_merge($property1['bundles'], $property2['bundles']);

        $property1['bundles'] = array_unique($mergedPropertyBundles);

        return $property1;
    }

}
