<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\Model\Generator;

use Exception;
use Spryker\Zed\Transfer\Business\Exception\TransferDefinitionMismatchException;

class TransferDefinitionMerger implements MergerInterface
{
    /**
     * @var string
     */
    public const ERROR_MESSAGE_PROPERTY_ATTRIBUTES_NOT_IDENTICAL =
        'Value mismatch for "%1$s.%2$s" transfer property. Value1: "%3$s"; Value2: "%4$s". ' .
        'To fix this, search for \'property name="%2$s"\' in the code base and fix the wrong one.';

    /**
     * @var array<string, array>
     */
    protected $mergedTransferDefinitions = [];

    /**
     * @param array<array> $transferDefinitions
     *
     * @return array<string, array>
     */
    public function merge(array $transferDefinitions)
    {
        $this->mergedTransferDefinitions = [];

        foreach ($transferDefinitions as $transferDefinition) {
            if (array_key_exists($transferDefinition['name'], $this->mergedTransferDefinitions)) {
                $this->mergedTransferDefinitions[$transferDefinition['name']] = $this->mergeDefinitions(
                    $this->mergedTransferDefinitions[$transferDefinition['name']],
                    $transferDefinition,
                    $transferDefinition['name'],
                );
            } else {
                $this->mergedTransferDefinitions[$transferDefinition['name']] = $transferDefinition;
            }
        }

        return $this->mergedTransferDefinitions;
    }

    /**
     * @param array<string, mixed> $existingDefinition
     * @param array<string, mixed> $definitionToMerge
     * @param string $transferName
     *
     * @return array<string, mixed>
     */
    protected function mergeDefinitions(array $existingDefinition, array $definitionToMerge, $transferName)
    {
        $this->assertTransferStrictModeIsConsistent($existingDefinition, $definitionToMerge);

        $mergedDefinition = [
            'name' => $existingDefinition['name'],
            'entity-namespace' => $existingDefinition['entity-namespace'] ?? null,
            'deprecated' => $this->mergeDeprecatedClassDefinition($existingDefinition, $definitionToMerge),
            'property' => $this->mergeProperty($existingDefinition['property'], $definitionToMerge['property'], $transferName),
        ];

        if (isset($existingDefinition[DefinitionNormalizer::KEY_STRICT_MODE])) {
            $mergedDefinition[DefinitionNormalizer::KEY_STRICT_MODE] = $existingDefinition[DefinitionNormalizer::KEY_STRICT_MODE];
        }

        return $mergedDefinition;
    }

    /**
     * @param array<string, mixed> $existingDefinition
     * @param array<string, mixed> $definitionToMerge
     *
     * @return string|null
     */
    protected function mergeDeprecatedClassDefinition(array $existingDefinition, array $definitionToMerge)
    {
        if (!isset($existingDefinition['deprecated'])) {
            $existingDefinition['deprecated'] = null;
        }
        if (!isset($definitionToMerge['deprecated'])) {
            $definitionToMerge['deprecated'] = null;
        }

        return $this->mergeDeprecatedAttributes($existingDefinition['deprecated'], $definitionToMerge['deprecated']);
    }

    /**
     * @param array<array> $existingProperties
     * @param array<array> $propertiesToMerge
     * @param string $transferName
     *
     * @return array<string, array>
     */
    protected function mergeProperty(array $existingProperties, array $propertiesToMerge, $transferName)
    {
        $mergedProperties = [];

        foreach ($existingProperties as $property) {
            $mergedProperties[$property['name']] = $property;
        }

        foreach ($propertiesToMerge as $propertyToMerge) {
            if (!array_key_exists($propertyToMerge['name'], $mergedProperties)) {
                $mergedProperties[$propertyToMerge['name']] = $propertyToMerge;

                continue;
            }

            $mergedProperties[$propertyToMerge['name']] = $this->mergeProperties(
                $mergedProperties[$propertyToMerge['name']],
                $propertyToMerge,
                $transferName,
            );
        }

        return $mergedProperties;
    }

    /**
     * @param array<string, mixed> $property
     * @param array<string, mixed> $propertyToMerge
     * @param string $transferName
     *
     * @throws \Exception
     *
     * @return array<string, mixed>
     */
    protected function mergeProperties(array $property, array $propertyToMerge, $transferName)
    {
        foreach ($propertyToMerge as $propertyName => $propertyValue) {
            $this->assertTransferPropertyStrictModeIsConsistent($property, $propertyToMerge, $transferName);

            if (!array_key_exists($propertyName, $property)) {
                $property[$propertyName] = $propertyValue;

                continue;
            }

            switch ($propertyName) {
                case 'bundles':
                    $property[$propertyName] = $this->mergePropertyBundles($property[$propertyName], $propertyValue);

                    break;
                case 'deprecated':
                    $property[$propertyName] = $this->mergeDeprecatedAttributes($property[$propertyName], $propertyValue);

                    break;
                default:
                    if ($propertyValue !== $property[$propertyName]) {
                        throw new Exception(sprintf(
                            static::ERROR_MESSAGE_PROPERTY_ATTRIBUTES_NOT_IDENTICAL,
                            $transferName,
                            $property['name'],
                            $property[$propertyName],
                            $propertyValue,
                        ));
                    }

                    break;
            }
        }

        return $property;
    }

    /**
     * @param array<string> $bundles1
     * @param array<string> $bundles2
     *
     * @return array<string>
     */
    protected function mergePropertyBundles(array $bundles1, array $bundles2)
    {
        $mergedPropertyBundles = array_merge($bundles1, $bundles2);

        return array_unique($mergedPropertyBundles);
    }

    /**
     * @param string|null $deprecated1
     * @param string|null $deprecated2
     *
     * @return string|null
     */
    protected function mergeDeprecatedAttributes($deprecated1, $deprecated2)
    {
        if ($deprecated1 === null && $deprecated2 === null) {
            return null;
        }

        if ($deprecated1 === null) {
            return $deprecated2;
        }

        return $deprecated1;
    }

    /**
     * @param array<string, mixed> $existingTransferDefinition
     * @param array<string, mixed> $transferDefinitionToMerge
     *
     * @throws \Spryker\Zed\Transfer\Business\Exception\TransferDefinitionMismatchException
     *
     * @return void
     */
    protected function assertTransferStrictModeIsConsistent(array $existingTransferDefinition, array $transferDefinitionToMerge): void
    {
        if ($this->validateNonMergeableAttribute(DefinitionNormalizer::KEY_STRICT_MODE, $existingTransferDefinition, $transferDefinitionToMerge)) {
            return;
        }

        throw new TransferDefinitionMismatchException(
            sprintf(
                'Strict mode violation detected for transfer %s. "strict" attribute value for this transfer must be identical across all definitions.',
                $existingTransferDefinition['name'],
            ),
        );
    }

    /**
     * @param array<string, mixed> $existingTransferProperty
     * @param array<string, mixed> $transferPropertyToMerge
     * @param string $transferName
     *
     * @throws \Spryker\Zed\Transfer\Business\Exception\TransferDefinitionMismatchException
     *
     * @return void
     */
    protected function assertTransferPropertyStrictModeIsConsistent(array $existingTransferProperty, array $transferPropertyToMerge, string $transferName): void
    {
        if ($this->validateNonMergeableAttribute(DefinitionNormalizer::KEY_STRICT_MODE, $existingTransferProperty, $transferPropertyToMerge)) {
            return;
        }

        throw new TransferDefinitionMismatchException(
            sprintf(
                'Strict mode violation detected for transfer property %s.%s. "strict" attribute value for this property must be identical across all definitions.',
                $transferName,
                $existingTransferProperty['name'],
            ),
        );
    }

    /**
     * @param string $attributeName
     * @param array<string, mixed> $existingDefinition
     * @param array<string, mixed> $definitionToMerge
     *
     * @return bool
     */
    protected function validateNonMergeableAttribute(string $attributeName, array $existingDefinition, array $definitionToMerge): bool
    {
        $existingAttributeValue = isset($existingDefinition[$attributeName]);
        $newAttributeValue = isset($definitionToMerge[$attributeName]);

        if ($existingAttributeValue !== $newAttributeValue) {
            return false;
        }

        return true;
    }
}
