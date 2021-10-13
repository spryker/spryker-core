<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\AclEntity;

interface AclEntityServiceInterface
{
    /**
     * Specification:
     * - Returns segment connector table name based on passed base table name.
     *
     * @api
     *
     * @param string $baseTableName
     *
     * @return string
     */
    public function generateSegmentConnectorTableName(string $baseTableName): string;

    /**
     * Specification:
     * - Returns segment connector table id column name based on passed connector table name.
     *
     * @api
     *
     * @param string $connectorTableName
     *
     * @return string
     */
    public function generateSegmentConnectorTableIdColumnName(string $connectorTableName): string;

    /**
     * Specification:
     * - Returns segment connector class name based on passed base class name.
     *
     * @api
     *
     * @param string $baseClass
     *
     * @return string
     */
    public function generateSegmentConnectorClassName(string $baseClass): string;

    /**
     * Specification:
     * - Returns segment connector relation name based on passed base class short name.
     *
     * @api
     *
     * @param string $baseClassShortName
     *
     * @return string
     */
    public function generateSegmentConnectorRelationName(string $baseClassShortName): string;

    /**
     * Specification:
     * - Returns segment connector getter method name based on passed base class name.
     *
     * @api
     *
     * @param string $baseClass
     *
     * @return string
     */
    public function generateSegmentConnectorGetter(string $baseClass): string;

    /**
     * Specification:
     * - Returns segment connector reference getter method name based on passed base class name.
     *
     * @api
     *
     * @param string $baseClass
     *
     * @return string
     */
    public function generateSegmentConnectorReferenceGetter(string $baseClass): string;

    /**
     * Specification:
     * - Returns segment connector reference setter method name based on passed base class name.
     *
     * @api
     *
     * @param string $baseClass
     *
     * @return string
     */
    public function generateSegmentConnectorReferenceSetter(string $baseClass): string;

    /**
     * Specification:
     * - Returns segment connector reference column name based on passed referenced table name.
     *
     * @api
     *
     * @param string $referencedTableName
     *
     * @return string
     */
    public function generateSegmentConnectorReferenceColumnName(string $referencedTableName): string;

    /**
     * Specification:
     * - Return segment connector table unique constaint name
     *
     * @api
     *
     * @param string $referencedTableName
     * @param string $referencedColumnName
     *
     * @return string
     */
    public function generateSegmentConnectorTableUniqueConstraintName(string $referencedTableName, string $referencedColumnName): string;
}
