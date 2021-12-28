<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Transfer\TypeValidation;

use InvalidArgumentException;

/**
 * @property array $transferMetadata
 */
trait TransferTypeValidatorTrait
{
    /**
     * @param string $propertyName
     * @param mixed $value
     *
     * @return void
     */
    protected function validateFromArrayValueType(string $propertyName, $value): void
    {
        $propertyExpectedTypes = $this->getFromArrayPropertyExpectedTypes($propertyName, $value);

        if ($this->checkValueTypeIsCorrect($value, $propertyExpectedTypes)) {
            return;
        }

        $message = sprintf(
            'Value passed to `%s::fromArray()` method under the `%s` key is expected to be of type(s) %s. %s is given',
            static::class,
            $propertyName,
            $propertyExpectedTypes,
            $this->getActualValueType($value),
        );
        $this->logTypeErrorMessage($message, 2);
    }

    /**
     * @param mixed $value
     * @param string $propertyExpectedTypes
     * @param string $fullyQualifiedCalleeMethodName
     *
     * @return void
     */
    protected function validateAddSetValueType($value, string $propertyExpectedTypes, string $fullyQualifiedCalleeMethodName): void
    {
        if ($this->checkValueTypeIsCorrect($value, $propertyExpectedTypes)) {
            return;
        }

        $message = sprintf(
            'Value passed to `%s()` method is expected to be of type(s) %s. %s is given',
            $fullyQualifiedCalleeMethodName,
            $propertyExpectedTypes,
            $this->getActualValueType($value),
        );
        $this->logTypeErrorMessage($message, 2);
    }

    /**
     * @param mixed $value
     * @param string $expectedValueTypes Expected types concatenated with '|' sign.
     *
     * @return bool
     */
    protected function checkValueTypeIsCorrect($value, string $expectedValueTypes): bool
    {
        if ($value === null) {
            return true;
        }

        $valueTypesCollection = explode('|', $expectedValueTypes);

        foreach ($valueTypesCollection as $valueType) {
            $typeAssertFunction = $this->getTypeAssertFunction($valueType);

            if ($typeAssertFunction($value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $varType
     *
     * @throws \InvalidArgumentException
     *
     * @return callable
     */
    protected function getTypeAssertFunction(string $varType): callable
    {
        if (preg_match('/^.*\[\]$/', $varType)) {
            return function ($value) use ($varType) {
                return is_array($value) && $this->checkArrayElementsAreOfExpectedType($value, str_replace('[]', '', $varType));
            };
        }

        if (class_exists($varType)) {
            return function ($value) use ($varType) {
                return $value instanceof $varType;
            };
        }

        if ($varType === 'boolean') {
            return 'is_bool';
        }

        $typeAssertFunctionName = 'is_' . $varType;

        if (!function_exists($typeAssertFunctionName)) {
            throw new InvalidArgumentException(
                sprintf('Variable type `%s` is not resolvable to an existing type assert function.', $varType),
            );
        }

        return $typeAssertFunctionName;
    }

    /**
     * @param array $array
     * @param string $elementType
     *
     * @return bool
     */
    protected function checkArrayElementsAreOfExpectedType(array $array, string $elementType): bool
    {
        $typeAssertFunction = $this->getTypeAssertFunction($elementType);

        foreach ($array as $arrayElement) {
            if (!$typeAssertFunction($arrayElement)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param mixed $value
     *
     * @return string
     */
    protected function getActualValueType($value): string
    {
        if (!is_array($value)) {
            return gettype($value);
        }

        if (!$value) {
            return 'array';
        }

        $firstElementKey = key($value);
        $firstElementType = gettype($value[$firstElementKey]);

        return $this->checkArrayElementsAreOfExpectedType($value, $firstElementType)
            ? $firstElementType . '[]'
            : 'mixed[]';
    }

    /**
     * @param string $message
     * @param int $stackTraceNestingLevel
     *
     * @return void
     */
    protected function logTypeErrorMessage(string $message, $stackTraceNestingLevel = 1): void
    {
        ['file' => $callerFileName, 'line' => $callerLineNumber] = debug_backtrace()[$stackTraceNestingLevel];
        $typeErrorMessage = sprintf(
            '%s. Called in %s:%d',
            $message,
            $callerFileName,
            $callerLineNumber,
        );

        file_put_contents($this->getLogFilePath(), $typeErrorMessage . PHP_EOL, FILE_APPEND);
    }

    /**
     * @param string $propertyName
     * @param mixed $value
     *
     * @return string
     */
    protected function getFromArrayPropertyExpectedTypes(string $propertyName, $value): string
    {
        $propertyType = $this->transferMetadata[$propertyName]['type'];
        $typeShim = $this->transferMetadata[$propertyName]['type_shim'];

        if ($propertyType === 'array' && is_string($value) && json_decode($value) !== null) {
            $propertyType = 'string';
        }

        if ($this->transferMetadata[$propertyName]['is_transfer']) {
            $propertyType = $this->transferMetadata[$propertyName]['is_collection'] ? '\ArrayObject|array' : sprintf('\%s|array', $propertyType);
        }

        if ($typeShim) {
            return sprintf('%s|%s', $propertyType, $typeShim);
        }

        return $propertyType;
    }

    /**
     * @return string
     */
    protected function getLogFilePath(): string
    {
        return sys_get_temp_dir() . '/transfer-type-error.log';
    }
}
