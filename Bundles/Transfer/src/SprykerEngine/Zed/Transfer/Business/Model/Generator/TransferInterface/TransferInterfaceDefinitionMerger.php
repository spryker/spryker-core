<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferInterface;

use SprykerEngine\Zed\Transfer\Business\Exception\PropertyAmbiguous;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\MergerInterface;

class TransferInterfaceDefinitionMerger implements MergerInterface
{

    const ERROR_MESSAGE_PROPERTIES_NOT_IDENTICALLY =
        'Property \'%1$s\' defined more than once with different attributes! To fix this, search for \'property name="%1$s"\' in the code base and fix the wrong one.';
    const KEY_BUNDLE = 'bundle';
    const KEY_CONTAINING_BUNDLE = 'containing bundle';
    const KEY_NAME = 'name';
    const KEY_PROPERTY = 'property';

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
            $key = $this->getMergedKeyName($transferDefinition);
            if (array_key_exists($key, $this->mergedTransferDefinitions)) {
                $transferDefinition = $this->mergeDefinitions($this->mergedTransferDefinitions[$key], $transferDefinition);
            }

            $this->mergedTransferDefinitions[$key] = $transferDefinition;
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
            self::KEY_BUNDLE => $existingDefinition[self::KEY_BUNDLE],
            self::KEY_CONTAINING_BUNDLE => $existingDefinition[self::KEY_CONTAINING_BUNDLE],
            self::KEY_NAME => $existingDefinition[self::KEY_NAME],
            self::KEY_PROPERTY => $this->mergeProperty($existingDefinition[self::KEY_PROPERTY], $definitionToMerge[self::KEY_PROPERTY]),
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
            $mergedProperties[$property[self::KEY_NAME]] = $property;
        }

        foreach ($propertiesToMerge as $property) {
            if (!array_key_exists($property[self::KEY_NAME], $mergedProperties)) {
                $mergedProperties[$property[self::KEY_NAME]] = $property;
            } elseif (!$this->propertiesAreIdentically($property, $mergedProperties[$property[self::KEY_NAME]])) {
                throw new PropertyAmbiguous(sprintf(self::ERROR_MESSAGE_PROPERTIES_NOT_IDENTICALLY, $property[self::KEY_NAME]));
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
        $diff = array_diff($property1, $property2);
        if (count($diff) === 0) {
            return true;
        }

        if (count($diff) === 1 && isset($diff[self::KEY_BUNDLE])) {
            return true;
        }

        return false;
    }

    /**
     * @param array $transferDefinition
     *
     * @return bool
     */
    protected function canBeMerged(array $transferDefinition)
    {
        $key = $this->getMergedKeyName($transferDefinition);
        if (array_key_exists($transferDefinition[$key], $this->mergedTransferDefinitions)) {
            if ($this->mergedTransferDefinitions[$key][self::KEY_CONTAINING_BUNDLE] === $transferDefinition[self::KEY_CONTAINING_BUNDLE]) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $transferDefinition
     *
     * @return string
     */
    protected function getMergedKeyName($transferDefinition)
    {
        $key = $transferDefinition[self::KEY_CONTAINING_BUNDLE] . $transferDefinition[self::KEY_NAME];

        return $key;
    }

}
