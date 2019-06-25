<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DocumentationGeneratorRestApi\Business\Analyzer;

use ReflectionClass;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class ResourceTransferAnalyzer implements ResourceTransferAnalyzerInterface
{
    protected const TRANSFER_NAME_PARTIAL_ATTRIBUTES = 'Attributes';
    protected const TRANSFER_NAME_PARTIAL_TRANSFER = 'Transfer';

    protected const SCHEMA_NAME_PARTIAL_ATTRIBUTES = 'Attributes';
    protected const SCHEMA_NAME_PARTIAL_COLLECTION = 'Collection';
    protected const SCHEMA_NAME_PARTIAL_DATA = 'Data';
    protected const SCHEMA_NAME_PARTIAL_RELATIONSHIPS = 'Relationships';
    protected const SCHEMA_NAME_PARTIAL_REQUEST = 'Request';
    protected const SCHEMA_NAME_PARTIAL_RESPONSE = 'Response';

    /**
     * @param string $transferClassName
     *
     * @return bool
     */
    public function isTransferValid(string $transferClassName): bool
    {
        return class_exists($transferClassName) && is_subclass_of($transferClassName, AbstractTransfer::class);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $transfer
     *
     * @return array
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
        return $this->createSchemaNameFromTransferClassName(
            $this->getTransferClassNamePartial($transferClassName),
            static::TRANSFER_NAME_PARTIAL_ATTRIBUTES . static::TRANSFER_NAME_PARTIAL_TRANSFER,
            static::SCHEMA_NAME_PARTIAL_REQUEST
        );
    }

    /**
     * @param string $transferClassName
     *
     * @return string
     */
    public function createRequestDataSchemaNameFromTransferClassName(string $transferClassName): string
    {
        return $this->createSchemaNameFromTransferClassName(
            $this->getTransferClassNamePartial($transferClassName),
            static::TRANSFER_NAME_PARTIAL_ATTRIBUTES . static::TRANSFER_NAME_PARTIAL_TRANSFER,
            static::SCHEMA_NAME_PARTIAL_REQUEST . static::SCHEMA_NAME_PARTIAL_DATA
        );
    }

    /**
     * @param string $transferClassName
     *
     * @return string
     */
    public function createRequestAttributesSchemaNameFromTransferClassName(string $transferClassName): string
    {
        return $this->createSchemaNameFromTransferClassName(
            $this->getTransferClassNamePartial($transferClassName),
            static::TRANSFER_NAME_PARTIAL_ATTRIBUTES . static::TRANSFER_NAME_PARTIAL_TRANSFER,
            static::SCHEMA_NAME_PARTIAL_REQUEST . static::SCHEMA_NAME_PARTIAL_ATTRIBUTES
        );
    }

    /**
     * @param string $transferClassName
     *
     * @return string
     */
    public function createResponseResourceSchemaNameFromTransferClassName(string $transferClassName): string
    {
        return $this->createSchemaNameFromTransferClassName(
            $this->getTransferClassNamePartial($transferClassName),
            static::TRANSFER_NAME_PARTIAL_ATTRIBUTES . static::TRANSFER_NAME_PARTIAL_TRANSFER,
            static::SCHEMA_NAME_PARTIAL_RESPONSE
        );
    }

    /**
     * @param string $transferClassName
     *
     * @return string
     */
    public function createResponseResourceDataSchemaNameFromTransferClassName(string $transferClassName): string
    {
        return $this->createSchemaNameFromTransferClassName(
            $this->getTransferClassNamePartial($transferClassName),
            static::TRANSFER_NAME_PARTIAL_ATTRIBUTES . static::TRANSFER_NAME_PARTIAL_TRANSFER,
            static::SCHEMA_NAME_PARTIAL_RESPONSE . static::SCHEMA_NAME_PARTIAL_DATA
        );
    }

    /**
     * @param string $transferClassName
     *
     * @return string
     */
    public function createResponseCollectionSchemaNameFromTransferClassName(string $transferClassName): string
    {
        return $this->createSchemaNameFromTransferClassName(
            $this->getTransferClassNamePartial($transferClassName),
            static::TRANSFER_NAME_PARTIAL_ATTRIBUTES . static::TRANSFER_NAME_PARTIAL_TRANSFER,
            static::SCHEMA_NAME_PARTIAL_COLLECTION . static::SCHEMA_NAME_PARTIAL_RESPONSE
        );
    }

    /**
     * @param string $transferClassName
     *
     * @return string
     */
    public function createResponseCollectionDataSchemaNameFromTransferClassName(string $transferClassName): string
    {
        return $this->createSchemaNameFromTransferClassName(
            $this->getTransferClassNamePartial($transferClassName),
            static::TRANSFER_NAME_PARTIAL_ATTRIBUTES . static::TRANSFER_NAME_PARTIAL_TRANSFER,
            static::SCHEMA_NAME_PARTIAL_COLLECTION . static::SCHEMA_NAME_PARTIAL_RESPONSE . static::SCHEMA_NAME_PARTIAL_DATA
        );
    }

    /**
     * @param string $transferClassName
     *
     * @return string
     */
    public function createResponseAttributesSchemaNameFromTransferClassName(string $transferClassName): string
    {
        return $this->createSchemaNameFromTransferClassName(
            $this->getTransferClassNamePartial($transferClassName),
            static::TRANSFER_NAME_PARTIAL_TRANSFER,
            ''
        );
    }

    /**
     * @param string $transferClassName
     *
     * @return string
     */
    public function createResourceRelationshipSchemaNameFromTransferClassName(string $transferClassName): string
    {
        return $this->createSchemaNameFromTransferClassName(
            $this->getTransferClassNamePartial($transferClassName),
            static::TRANSFER_NAME_PARTIAL_ATTRIBUTES . static::TRANSFER_NAME_PARTIAL_TRANSFER,
            static::SCHEMA_NAME_PARTIAL_RELATIONSHIPS
        );
    }

    /**
     * @param string $transferClassName
     * @param string $removal
     * @param string $addition
     *
     * @return string
     */
    protected function createSchemaNameFromTransferClassName(string $transferClassName, string $removal, string $addition): string
    {
        return str_replace($removal, $addition, $transferClassName);
    }

    /**
     * @param string $transferClassName
     *
     * @return string
     */
    protected function getTransferClassNamePartial(string $transferClassName): string
    {
        $transferClassNameExploded = $this->getTransferClassNameExploded($transferClassName);

        return end($transferClassNameExploded);
    }

    /**
     * @param string $transferClassName
     *
     * @return string[]
     */
    protected function getTransferClassNameExploded(string $transferClassName): array
    {
        return explode('\\', $transferClassName);
    }
}
