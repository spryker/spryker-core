<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\Model\Generator;

use Exception;
use Psr\Log\LoggerInterface;
use Spryker\Shared\Transfer\TransferConstants;
use Spryker\Zed\Transfer\Business\Exception\TransferDefinitionMismatchException;
use Spryker\Zed\Transfer\TransferConfig;

class TransferDefinitionMerger implements MergerInterface
{
    /**
     * @var string
     */
    public const ERROR_MESSAGE_PROPERTY_ATTRIBUTES_NOT_IDENTICAL =
        'Value mismatch for "%1$s.%2$s" transfer property. Value1: "%3$s"; Value2: "%4$s". ' .
        'To fix this, search for \'property name="%2$s"\' in the code base and fix the wrong one.';

    /**
     * @var string
     */
    protected const PROPERTY_DESCRIPTION_DELIMITER = ', ';

    /**
     * @var string
     */
    protected const WARNING_MESSAGE_PROPERTY_DESCRIPTION_NOT_IDENTICAL = 'Description mismatch for "%1$s.%2$s" transfer property.' .
        '⚠️ Description1: "%3$s"; Description2: "%4$s".' .
        'To fix this, search for \'property name="%2$s"\' in the code base and fix the wrong one.';

    /**
     * @var array<string, array>
     */
    protected $mergedTransferDefinitions = [];

    /**
     * @var string
     */
    protected const TRANSFER_SUFFIX = 'Transfer';

    /**
     * @var \Spryker\Zed\Transfer\TransferConfig
     */
    protected $transferConfig;

    /**
     * @var \Psr\Log\LoggerInterface|null
     */
    protected $messenger;

    /**
     * @var array<string, array<string>>
     */
    protected array $originalNames = [];

    /**
     * @param \Spryker\Zed\Transfer\TransferConfig $transferConfig
     */
    public function __construct(TransferConfig $transferConfig)
    {
        $this->transferConfig = $transferConfig;
    }

    /**
     * @param array<array> $transferDefinitions
     * @param \Psr\Log\LoggerInterface|null $messenger
     *
     * @return array<string, array>
     */
    public function merge(array $transferDefinitions, ?LoggerInterface $messenger = null): array
    {
        $this->messenger = $messenger;
        $this->mergedTransferDefinitions = [];
        $this->originalNames = [];

        $this->processTransferDefinitions($transferDefinitions);

        return $this->createDefinitionsWithOriginalNames();
    }

    /**
     * @param array<array> $transferDefinitions
     *
     * @return void
     */
    protected function processTransferDefinitions(array $transferDefinitions): void
    {
        /** @var array<string, string> $transferDefinition */
        foreach ($transferDefinitions as $transferDefinition) {
            $originalName = $transferDefinition['name'];
            $normalizedName = $this->getNormalizedTransferName($originalName);

            if (!isset($this->originalNames[$normalizedName])) {
                $this->originalNames[$normalizedName] = [];
            }

            if (!in_array($originalName, $this->originalNames[$normalizedName])) {
                $this->originalNames[$normalizedName][] = $originalName;
            }

            if (isset($this->mergedTransferDefinitions[$normalizedName])) {
                $this->mergedTransferDefinitions[$normalizedName] = $this->mergeDefinitions(
                    $this->mergedTransferDefinitions[$normalizedName],
                    $transferDefinition,
                    $normalizedName,
                );
            } else {
                $this->mergedTransferDefinitions[$normalizedName] = $transferDefinition;
            }
        }
    }

    /**
     * Creates the final result by restoring original transfer names to their merged definitions.
     *
     * This method takes the merged definitions (which use normalized names) and creates
     * separate definition entries for each original name that was normalized to the same value.
     * For example, if 'UserTransfer' and 'User' both normalize to 'User', this method
     * will create two separate definition entries with their respective original names
     * but identical merged content.
     *
     * @return array<string, array>
     */
    protected function createDefinitionsWithOriginalNames(): array
    {
        $result = [];
        foreach ($this->mergedTransferDefinitions as $normalizedName => $definition) {
            foreach ($this->originalNames[$normalizedName] as $originalName) {
                $definitionCopy = $definition;
                $definitionCopy['name'] = $originalName;
                $result[$originalName] = $definitionCopy;
            }
        }

        return $result;
    }

    /**
     * @param array<string, mixed> $existingDefinition
     * @param array<string, mixed> $definitionToMerge
     * @param string $transferName
     *
     * @return array<string, mixed>
     */
    protected function mergeDefinitions(array $existingDefinition, array $definitionToMerge, $transferName): array
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
    protected function mergeDeprecatedClassDefinition(array $existingDefinition, array $definitionToMerge): ?string
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
     * @param array<array<string, mixed>> $existingProperties
     * @param array<array<string, mixed>> $propertiesToMerge
     * @param string $transferName
     *
     * @return array<string, array>
     */
    protected function mergeProperty(array $existingProperties, array $propertiesToMerge, $transferName): array
    {
        $mergedProperties = [];

        /** @var array<string, string> $property */
        foreach ($existingProperties as $property) {
            $mergedProperties[$property['name']] = $property;
        }

        /** @var array<string, string> $propertyToMerge */
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
    protected function mergeProperties(array $property, array $propertyToMerge, $transferName): array
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
                case 'description': // phpcs:ignore
                    if (!$this->isDefaultMergeStrategy()) {
                        $property[$propertyName] = $this->mergePropertyDescription($property[$propertyName], $propertyValue, $property['name'], $transferName);

                        break;
                    }
                default:
                    if (
                        $this->transferConfig->isProjectTransferOverrideActive() &&
                        in_array($propertyName, $this->transferConfig->getTransferPropertyAttributesAvailableForProjectOverride())
                    ) {
                        break;
                    }

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
    protected function mergePropertyBundles(array $bundles1, array $bundles2): array
    {
        $mergedPropertyBundles = array_merge($bundles1, $bundles2);

        return array_unique($mergedPropertyBundles);
    }

    /**
     * @param string $description1
     * @param string $description2
     * @param string $propertyName
     * @param string $transferName
     *
     * @return string|null
     */
    protected function mergePropertyDescription(string $description1, string $description2, $propertyName, $transferName): ?string
    {
        if ($description1 === $description2) {
            return $description1;
        }

        if ($this->isConcatMergeStrategy()) {
            return sprintf('%s%s%s', $description1, static::PROPERTY_DESCRIPTION_DELIMITER, $description2);
        }

        if ($this->isGetFirstMergeStrategy()) {
            $this->logWarning(
                sprintf(
                    static::WARNING_MESSAGE_PROPERTY_DESCRIPTION_NOT_IDENTICAL,
                    $transferName,
                    $propertyName,
                    $description1,
                    $description2,
                ),
            );

            return $description1;
        }

        return null;
    }

    /**
     * @param string|null $deprecated1
     * @param string|null $deprecated2
     *
     * @return string|null
     */
    protected function mergeDeprecatedAttributes($deprecated1, $deprecated2): ?string
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

    /**
     * @return bool
     */
    protected function isDefaultMergeStrategy(): bool
    {
        return $this->transferConfig->getPropertyDescriptionMergeStrategy() === TransferConstants::PROPERTY_DESCRIPTION_MERGE_STRATEGY_DEFAULT;
    }

    /**
     * @return bool
     */
    protected function isConcatMergeStrategy(): bool
    {
        return $this->transferConfig->getPropertyDescriptionMergeStrategy() === TransferConstants::PROPERTY_DESCRIPTION_MERGE_STRATEGY_CONCAT;
    }

    /**
     * @return bool
     */
    protected function isGetFirstMergeStrategy(): bool
    {
        return $this->transferConfig->getPropertyDescriptionMergeStrategy() === TransferConstants::PROPERTY_DESCRIPTION_MERGE_STRATEGY_GET_FIRST;
    }

    /**
     * @param string $message
     * @param array<mixed> $context
     *
     * @return void
     */
    protected function logWarning(string $message, array $context = [])
    {
        if ($this->messenger !== null) {
            $this->messenger->warning($message, $context);

            return;
        }
    }

    /**
     * @param string $transferName
     *
     * @return string
     */
    protected function getNormalizedTransferName(string $transferName): string
    {
        if ($this->hasTransferSuffix($transferName)) {
            return substr($transferName, 0, -strlen(static::TRANSFER_SUFFIX));
        }

        return $transferName;
    }

    /**
     * @param string $transferName
     *
     * @return bool
     */
    protected function hasTransferSuffix(string $transferName): bool
    {
        if ($transferName === static::TRANSFER_SUFFIX) {
            return false;
        }

        return str_ends_with($transferName, static::TRANSFER_SUFFIX);
    }
}
