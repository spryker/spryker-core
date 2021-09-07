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
     * @param array $transferDefinitions
     *
     * @return array
     */
    public function normalizeDefinitions(array $transferDefinitions)
    {
        $normalizedDefinitions = [];
        foreach ($transferDefinitions as $transferDefinition) {
            $normalizedDefinition = [
                self::KEY_BUNDLE => $transferDefinition[self::KEY_BUNDLE],
                self::KEY_CONTAINING_BUNDLE => $transferDefinition[self::KEY_CONTAINING_BUNDLE],
                self::KEY_NAME => $transferDefinition[self::KEY_NAME],
                self::KEY_DEPRECATED => isset($transferDefinition[self::KEY_DEPRECATED]) ? $transferDefinition[self::KEY_DEPRECATED] : null,
                self::KEY_PROPERTY => $this->normalizeAttributes($transferDefinition[self::KEY_PROPERTY] ?? [], $transferDefinition[self::KEY_BUNDLE]),
                self::KEY_STRICT_MODE => $transferDefinition[static::KEY_STRICT_MODE] ?? null,
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
     * @param array $attributes
     * @param string $bundle
     *
     * @return array
     */
    protected function addBundleToAttributes(array $attributes, $bundle)
    {
        foreach ($attributes as &$attribute) {
            $attribute[self::KEY_BUNDLES] = [$bundle];
        }

        return $attributes;
    }

    /**
     * @param array $transferDefinition
     *
     * @return array
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
                $isTransferInStrictMode
            );
        }

        return $transferDefinition;
    }

    /**
     * @param array $transferDefinition
     *
     * @return array
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
     * @param array $propertyDefinitions
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
