<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\AclEntity\SegmentConnectorGenerator;

interface SegmentConnectorGeneratorInterface
{
    /**
     * @param string $baseTableName
     *
     * @return string
     */
    public function generateConnectorTableName(string $baseTableName): string;

    /**
     * @param string $connectorTableName
     *
     * @return string
     */
    public function generateConnectorTableIdColumnName(string $connectorTableName): string;

    /**
     * @param string $baseClass
     *
     * @return string
     */
    public function generateConnectorClassName(string $baseClass): string;

    /**
     * @param string $baseClassName
     *
     * @return string
     */
    public function generateConnectorRelationName(string $baseClassName): string;

    /**
     * @param string $baseClass
     *
     * @return string
     */
    public function generateConnectorGetter(string $baseClass): string;

    /**
     * @param string $baseClass
     *
     * @return string
     */
    public function generateConnectorReferenceGetter(string $baseClass): string;

    /**
     * @param string $baseClass
     *
     * @return string
     */
    public function generateConnectorReferenceSetter(string $baseClass): string;

    /**
     * @param string $referencedTableName
     *
     * @return string
     */
    public function generateConnectorReferenceColumnName(string $referencedTableName): string;

    /**
     * @param string $referencedTableName
     * @param string $referencedColumnName
     *
     * @return string
     */
    public function generateSegmentConnectorTableUniqueConstraintName(string $referencedTableName, string $referencedColumnName): string;
}
