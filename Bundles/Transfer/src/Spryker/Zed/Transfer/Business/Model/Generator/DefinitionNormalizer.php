<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\Model\Generator;

class DefinitionNormalizer implements DefinitionNormalizerInterface
{
    /**
     * @var string
     */
    public const KEY_BUNDLE = 'bundle';

    /**
     * @var string
     */
    public const KEY_CONTAINING_BUNDLE = 'containing bundle';

    /**
     * @var string
     */
    public const KEY_NAME = 'name';

    /**
     * @var string
     */
    public const KEY_PROPERTY = 'property';

    /**
     * @var string
     */
    public const KEY_BUNDLES = 'bundles';

    /**
     * @var string
     */
    public const KEY_DEPRECATED = 'deprecated';

    /**
     * @var string
     */
    public const KEY_STRICT_MODE = 'strict';

    /**
     * @param array<array> $transferDefinitions
     *
     * @return array<array>
     */
    public function normalizeDefinitions(array $transferDefinitions)
    {
        $normalizedDefinitions = [];
        foreach ($transferDefinitions as $transferDefinition) {
            $normalizedDefinition = [
                static::KEY_BUNDLE => $transferDefinition[static::KEY_BUNDLE],
                static::KEY_CONTAINING_BUNDLE => $transferDefinition[static::KEY_CONTAINING_BUNDLE],
                static::KEY_NAME => $transferDefinition[static::KEY_NAME],
                static::KEY_DEPRECATED => $transferDefinition[static::KEY_DEPRECATED] ?? null,
                static::KEY_PROPERTY => $this->normalizeAttributes($transferDefinition[static::KEY_PROPERTY] ?? [], $transferDefinition[static::KEY_BUNDLE]),
                static::KEY_STRICT_MODE => $transferDefinition[static::KEY_STRICT_MODE] ?? null,
            ];

            $normalizedDefinitions[] = $this->normalizeStrictMode($normalizedDefinition);
        }

        return $normalizedDefinitions;
    }

    /**
     * @param array $attributes
     * @param string $module
     *
     * @return array
     */
    protected function normalizeAttributes(array $attributes, $module)
    {
        if ($attributes === []) {
            return [];
        }

        if (isset($attributes[0])) {
            return $this->addBundleToAttributes($attributes, $module);
        }

        return $this->addBundleToAttributes([$attributes], $module);
    }

    /**
     * @param array<array> $attributes
     * @param string $bundle
     *
     * @return array<array>
     */
    protected function addBundleToAttributes(array $attributes, $bundle)
    {
        foreach ($attributes as &$attribute) {
            $attribute[static::KEY_BUNDLES] = [$bundle];
        }

        return $attributes;
    }

    /**
     * @param array<string, mixed> $transferDefinition
     *
     * @return array<string, mixed>
     */
    protected function normalizeStrictMode(array $transferDefinition): array
    {
        $transferDefinition = $this->normalizeTransferDefinitionStrictMode($transferDefinition);
        $propertyDefinitions = $transferDefinition[static::KEY_PROPERTY] ?? [];
        $isTransferInStrictMode = $transferDefinition[static::KEY_STRICT_MODE] ?? false;

        if ($propertyDefinitions) {
            $propertyDefinitions = isset($propertyDefinitions[0]) ? $propertyDefinitions : [$propertyDefinitions];
            $transferDefinition[static::KEY_PROPERTY] = $this->normalizeTransferPropertyDefinitionStrictMode(
                $propertyDefinitions,
                $isTransferInStrictMode,
            );
        }

        return $transferDefinition;
    }

    /**
     * @param array<string, mixed> $transferDefinition
     *
     * @return array<string, mixed>
     */
    protected function normalizeTransferDefinitionStrictMode(array $transferDefinition): array
    {
        $isTransferInStrictMode = isset($transferDefinition[static::KEY_STRICT_MODE])
            ? filter_var($transferDefinition[static::KEY_STRICT_MODE], FILTER_VALIDATE_BOOLEAN)
            : false;

        if ($isTransferInStrictMode) {
            $transferDefinition[static::KEY_STRICT_MODE] = true;
        }

        return $transferDefinition;
    }

    /**
     * @param array<string, mixed> $propertyDefinitions
     * @param bool $isTransferInStrictMode
     *
     * @return array
     */
    protected function normalizeTransferPropertyDefinitionStrictMode(array $propertyDefinitions, bool $isTransferInStrictMode): array
    {
        $normalizedPropertyDefinitions = [];

        foreach ($propertyDefinitions as $propertyDefinition) {
            $isPropertyInStrictMode = isset($propertyDefinition[static::KEY_STRICT_MODE])
                ? filter_var($propertyDefinition[static::KEY_STRICT_MODE], FILTER_VALIDATE_BOOLEAN)
                : $isTransferInStrictMode;

            if ($isPropertyInStrictMode) {
                $propertyDefinition[static::KEY_STRICT_MODE] = true;
            }

            $normalizedPropertyDefinitions[] = $propertyDefinition;
        }

        return $normalizedPropertyDefinitions;
    }
}
