<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\AclEntity\SegmentConnectorGenerator;

class SegmentConnectorGenerator implements SegmentConnectorGeneratorInterface
{
    public const CONNECTOR_CLASS_TEMPLATE = '%sAclEntitySegment%s';
    public const ENTITY_PREFIX_DEFAULT = 'Spy';

    protected const CONNECTOR_TABLE_TEMPLATE = '%sacl_entity_segment_%s';
    protected const CONNECTOR_TABLE_ID_COLUMN_NAME_TEMPLATE = 'id_%s';
    protected const CONNECTOR_RELATION_TEMPLATE = '%s.%s';
    protected const CONNECTOR_GETTER_TEMPLATE = 'get%sAclEntitySegment%ss';
    protected const CONNECTOR_REFERENCE_COLUMN_TEMPLATE = 'fk_%s';
    protected const CONNECTOR_REFERENCE_GETTER_TEMPLATE = 'getFk%s';
    protected const CONNECTOR_REFERENCE_SETTER_TEMPLATE = 'setFk%s';
    protected const CONNECTOR_REFERENCE_FILTER_QUERY_TEMPLATE = 'filterByFk%s';
    protected const CONNECTOR_UNIQUE_CONTAINT_TEMPLATE = '%s-%s-fk_acl_entity_segment-unique-key';

    protected const TABLE_PREFIX_DEFAULT = 'spy_';

    /**
     * @param string $baseTableName
     *
     * @return string
     */
    public function generateConnectorTableName(string $baseTableName): string
    {
        return sprintf(
            static::CONNECTOR_TABLE_TEMPLATE,
            static::TABLE_PREFIX_DEFAULT,
            $this->purifyTableName($baseTableName)
        );
    }

    /**
     * @param string $connectorTableName
     *
     * @return string
     */
    public function generateConnectorTableIdColumnName(string $connectorTableName): string
    {
        return sprintf(static::CONNECTOR_TABLE_ID_COLUMN_NAME_TEMPLATE, $this->purifyTableName($connectorTableName));
    }

    /**
     * @param string $baseClass
     *
     * @return string
     */
    public function generateConnectorClassName(string $baseClass): string
    {
        $baseClassShort = basename(str_replace('\\', '/', $baseClass));
        $namespace = substr($baseClass, 0, -strlen($baseClassShort));

        return $namespace . sprintf(
            static::CONNECTOR_CLASS_TEMPLATE,
            static::ENTITY_PREFIX_DEFAULT,
            $this->purifyClassName($baseClassShort)
        );
    }

    /**
     * @param string $baseClassName
     *
     * @return string
     */
    public function generateConnectorRelationName(string $baseClassName): string
    {
        return sprintf(
            static::CONNECTOR_RELATION_TEMPLATE,
            $this->generateShortClassName($baseClassName),
            $this->generateConnectorClassName($this->generateShortClassName($baseClassName))
        );
    }

    /**
     * @param string $baseClass
     *
     * @return string
     */
    public function generateConnectorGetter(string $baseClass): string
    {
        return sprintf(
            static::CONNECTOR_GETTER_TEMPLATE,
            static::ENTITY_PREFIX_DEFAULT,
            $this->purifyClassName($this->generateShortClassName($baseClass))
        );
    }

    /**
     * @param string $baseClass
     *
     * @return string
     */
    public function generateConnectorReferenceGetter(string $baseClass): string
    {
        $propertyName = $this->generateShortClassName($this->purifyClassName($baseClass));
        if (strpos($propertyName, static::ENTITY_PREFIX_DEFAULT) === 0) {
            $propertyName = substr($propertyName, strlen(static::ENTITY_PREFIX_DEFAULT));
        }

        return sprintf(static::CONNECTOR_REFERENCE_GETTER_TEMPLATE, $propertyName);
    }

    /**
     * @param string $baseClass
     *
     * @return string
     */
    public function generateConnectorReferenceSetter(string $baseClass): string
    {
        $propertyName = $this->generateShortClassName($this->purifyClassName($baseClass));
        if (strpos($propertyName, static::ENTITY_PREFIX_DEFAULT) === 0) {
            $propertyName = substr($propertyName, strlen(static::ENTITY_PREFIX_DEFAULT));
        }

        return sprintf(static::CONNECTOR_REFERENCE_SETTER_TEMPLATE, $propertyName);
    }

    /**
     * @param string $referencedTableName
     * @param string $referencedColumnName
     *
     * @return string
     */
    public function generateSegmentConnectorTableUniqueConstraintName(string $referencedTableName, string $referencedColumnName): string
    {
        $referencedTableName = $this->purifyTableName($referencedTableName);

        return sprintf(
            static::CONNECTOR_UNIQUE_CONTAINT_TEMPLATE,
            $referencedTableName,
            $referencedColumnName
        );
    }

    /**
     * @param string $referencedTableName
     *
     * @return string
     */
    public function generateConnectorReferenceColumnName(string $referencedTableName): string
    {
        $referencedTableName = $this->purifyTableName($referencedTableName);

        return sprintf(static::CONNECTOR_REFERENCE_COLUMN_TEMPLATE, $referencedTableName);
    }

    /**
     * @param string $baseClass
     *
     * @return string
     */
    protected function generateShortClassName(string $baseClass): string
    {
        return basename(str_replace('\\', '/', $baseClass));
    }

    /**
     * @param string $baseClass
     *
     * @return string
     */
    protected function purifyClassName(string $baseClass): string
    {
        if (strpos($baseClass, static::ENTITY_PREFIX_DEFAULT) === 0) {
            $baseClass = substr($baseClass, strlen(static::ENTITY_PREFIX_DEFAULT));
        }

        return $baseClass;
    }

    /**
     * @param string $tableName
     *
     * @return string
     */
    protected function purifyTableName(string $tableName): string
    {
        if (strpos($tableName, static::TABLE_PREFIX_DEFAULT) === 0) {
            $tableName = substr($tableName, strlen(static::TABLE_PREFIX_DEFAULT));
        }

        return $tableName;
    }
}
