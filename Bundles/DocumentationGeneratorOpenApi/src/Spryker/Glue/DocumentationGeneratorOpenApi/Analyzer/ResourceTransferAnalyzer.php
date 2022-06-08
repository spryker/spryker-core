<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorOpenApi\Analyzer;

use ReflectionClass;
use Spryker\Shared\Kernel\Transfer\AbstractEntityTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class ResourceTransferAnalyzer implements ResourceTransferAnalyzerInterface
{
    /**
     * @var string
     */
    protected const TRANSFER_NAME_PARTIAL_ATTRIBUTES = 'Attributes';

    /**
     * @var string
     */
    protected const TRANSFER_NAME_PARTIAL_TRANSFER = 'Transfer';

    /**
     * @var string
     */
    protected const SCHEMA_NAME_PARTIAL_ATTRIBUTES = 'Attributes';

    /**
     * @var string
     */
    protected const SCHEMA_NAME_PARTIAL_COLLECTION = 'Collection';

    /**
     * @var string
     */
    protected const SCHEMA_NAME_PARTIAL_DATA = 'Data';

    /**
     * @var string
     */
    protected const SCHEMA_NAME_PARTIAL_RELATIONSHIPS = 'Relationships';

    /**
     * @var string
     */
    protected const SCHEMA_NAME_PARTIAL_INCLUDED = 'Included';

    /**
     * @var string
     */
    protected const SCHEMA_NAME_PARTIAL_REQUEST = 'Request';

    /**
     * @var string
     */
    protected const SCHEMA_NAME_PARTIAL_RESPONSE = 'Response';

    /**
     * @var string
     */
    protected const KEY_REST_REQUEST_PARAMETER = 'rest_request_parameter';

    /**
     * @var string
     */
    protected const REST_REQUEST_BODY_PARAMETER_REQUIRED = 'required';

    /**
     * @param string $transferClassName
     *
     * @return bool
     */
    public function isTransferValid(string $transferClassName): bool
    {
        return class_exists($transferClassName)
            && is_subclass_of($transferClassName, AbstractTransfer::class)
            && !is_subclass_of($transferClassName, AbstractEntityTransfer::class);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer
     *
     * @return array<mixed>
     */
    public function getTransferMetadata(AbstractTransfer $transfer): array
    {
        $transferReflection = new ReflectionClass($transfer);
        $transferMetadata = $transferReflection->getProperty('transferMetadata');
        $transferMetadata->setAccessible(true);

        return $transferMetadata->getValue($transfer);
    }

    /**
     * @param string $transferClassName
     *
     * @return string
     */
    public function createRequestSchemaNameFromTransferClassName(string $transferClassName): string
    {
        return str_replace(
            static::TRANSFER_NAME_PARTIAL_ATTRIBUTES . static::TRANSFER_NAME_PARTIAL_TRANSFER,
            static::SCHEMA_NAME_PARTIAL_REQUEST,
            $this->getTransferClassNamePartial($transferClassName),
        );
    }

    /**
     * @param string $transferClassName
     *
     * @return string
     */
    public function createRequestDataSchemaNameFromTransferClassName(string $transferClassName): string
    {
        return str_replace(
            static::TRANSFER_NAME_PARTIAL_ATTRIBUTES . static::TRANSFER_NAME_PARTIAL_TRANSFER,
            static::SCHEMA_NAME_PARTIAL_REQUEST . static::SCHEMA_NAME_PARTIAL_DATA,
            $this->getTransferClassNamePartial($transferClassName),
        );
    }

    /**
     * @param string $transferClassName
     *
     * @return string
     */
    public function createRequestAttributesSchemaNameFromTransferClassName(string $transferClassName): string
    {
        return str_replace(
            static::TRANSFER_NAME_PARTIAL_ATTRIBUTES . static::TRANSFER_NAME_PARTIAL_TRANSFER,
            static::SCHEMA_NAME_PARTIAL_REQUEST . static::SCHEMA_NAME_PARTIAL_ATTRIBUTES,
            $this->getTransferClassNamePartial($transferClassName),
        );
    }

    /**
     * @param string $transferClassName
     *
     * @return string
     */
    public function createResponseResourceSchemaNameFromTransferClassName(string $transferClassName): string
    {
        return str_replace(
            static::TRANSFER_NAME_PARTIAL_ATTRIBUTES . static::TRANSFER_NAME_PARTIAL_TRANSFER,
            static::SCHEMA_NAME_PARTIAL_RESPONSE,
            $this->getTransferClassNamePartial($transferClassName),
        );
    }

    /**
     * @param string $transferClassName
     *
     * @return string
     */
    public function createResponseResourceDataSchemaNameFromTransferClassName(string $transferClassName): string
    {
        return str_replace(
            static::TRANSFER_NAME_PARTIAL_ATTRIBUTES . static::TRANSFER_NAME_PARTIAL_TRANSFER,
            static::SCHEMA_NAME_PARTIAL_RESPONSE . static::SCHEMA_NAME_PARTIAL_DATA,
            $this->getTransferClassNamePartial($transferClassName),
        );
    }

    /**
     * @param string $transferClassName
     *
     * @return string
     */
    public function createResponseCollectionSchemaNameFromTransferClassName(string $transferClassName): string
    {
        return str_replace(
            static::TRANSFER_NAME_PARTIAL_ATTRIBUTES . static::TRANSFER_NAME_PARTIAL_TRANSFER,
            static::SCHEMA_NAME_PARTIAL_COLLECTION . static::SCHEMA_NAME_PARTIAL_RESPONSE,
            $this->getTransferClassNamePartial($transferClassName),
        );
    }

    /**
     * @param string $transferClassName
     *
     * @return string
     */
    public function createResponseCollectionDataSchemaNameFromTransferClassName(string $transferClassName): string
    {
        return str_replace(
            static::TRANSFER_NAME_PARTIAL_ATTRIBUTES . static::TRANSFER_NAME_PARTIAL_TRANSFER,
            static::SCHEMA_NAME_PARTIAL_COLLECTION . static::SCHEMA_NAME_PARTIAL_RESPONSE . static::SCHEMA_NAME_PARTIAL_DATA,
            $this->getTransferClassNamePartial($transferClassName),
        );
    }

    /**
     * @param string $transferClassName
     *
     * @return string
     */
    public function createResponseAttributesSchemaNameFromTransferClassName(string $transferClassName): string
    {
        return str_replace(
            static::TRANSFER_NAME_PARTIAL_TRANSFER,
            '',
            $this->getTransferClassNamePartial($transferClassName),
        );
    }

    /**
     * @param string $transferClassName
     *
     * @return string
     */
    public function createResourceRelationshipSchemaNameFromTransferClassName(string $transferClassName): string
    {
        return str_replace(
            static::TRANSFER_NAME_PARTIAL_ATTRIBUTES . static::TRANSFER_NAME_PARTIAL_TRANSFER,
            static::SCHEMA_NAME_PARTIAL_RELATIONSHIPS,
            $this->getTransferClassNamePartial($transferClassName),
        );
    }

    /**
     * @param string $transferClassName
     *
     * @return string
     */
    public function createIncludedSchemaNameFromTransferClassName(string $transferClassName): string
    {
        return str_replace(
            static::TRANSFER_NAME_PARTIAL_ATTRIBUTES . static::TRANSFER_NAME_PARTIAL_TRANSFER,
            static::SCHEMA_NAME_PARTIAL_INCLUDED,
            $this->getTransferClassNamePartial($transferClassName),
        );
    }

    /**
     * @param string $transferClassName
     *
     * @return bool
     */
    public function isRequestSchemaRequired(string $transferClassName): bool
    {
        /** @var \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer */
        $transfer = new $transferClassName();
        $transferMetadata = $this->getTransferMetadata($transfer);
        foreach ($transferMetadata as $metadataParameter) {
            if ($metadataParameter[static::KEY_REST_REQUEST_PARAMETER] === static::REST_REQUEST_BODY_PARAMETER_REQUIRED) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array<mixed> $propertyMetaData
     *
     * @return bool
     */
    public function isRequestParameterRequired(array $propertyMetaData): bool
    {
        return isset($propertyMetaData[static::KEY_REST_REQUEST_PARAMETER])
            && $propertyMetaData[static::KEY_REST_REQUEST_PARAMETER]
            && $propertyMetaData[static::KEY_REST_REQUEST_PARAMETER] === static::REST_REQUEST_BODY_PARAMETER_REQUIRED;
    }

    /**
     * @param string $transferClassName
     *
     * @return string
     */
    protected function getTransferClassNamePartial(string $transferClassName): string
    {
        $transferClassNameExploded = $this->getTransferClassNameExploded($transferClassName);

        /** @phpstan-var string */
        return end($transferClassNameExploded);
    }

    /**
     * @param string $transferClassName
     *
     * @return array<string>
     */
    protected function getTransferClassNameExploded(string $transferClassName): array
    {
        return explode('\\', $transferClassName);
    }
}
