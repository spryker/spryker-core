<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\AclEntity;

use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\AclEntity\AclEntityServiceFactory getFactory()
 */
class AclEntityService extends AbstractService implements AclEntityServiceInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $baseTableName
     *
     * @return string
     */
    public function generateSegmentConnectorTableName(string $baseTableName): string
    {
        return $this->getFactory()->createSegmentConnectorGenerator()->generateConnectorTableName($baseTableName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $connectorTableName
     *
     * @return string
     */
    public function generateSegmentConnectorTableIdColumnName(string $connectorTableName): string
    {
        return $this->getFactory()->createSegmentConnectorGenerator()->generateConnectorTableIdColumnName($connectorTableName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $baseClass
     *
     * @return string
     */
    public function generateSegmentConnectorClassName(string $baseClass): string
    {
        return $this->getFactory()->createSegmentConnectorGenerator()->generateConnectorClassName($baseClass);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $baseClassShortName
     *
     * @return string
     */
    public function generateSegmentConnectorRelationName(string $baseClassShortName): string
    {
        return $this->getFactory()->createSegmentConnectorGenerator()->generateConnectorRelationName($baseClassShortName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $baseClass
     *
     * @return string
     */
    public function generateSegmentConnectorGetter(string $baseClass): string
    {
        return $this->getFactory()->createSegmentConnectorGenerator()->generateConnectorGetter($baseClass);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $baseClass
     *
     * @return string
     */
    public function generateSegmentConnectorReferenceGetter(string $baseClass): string
    {
        return $this->getFactory()->createSegmentConnectorGenerator()->generateConnectorReferenceGetter($baseClass);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $baseClass
     *
     * @return string
     */
    public function generateSegmentConnectorReferenceSetter(string $baseClass): string
    {
        return $this->getFactory()->createSegmentConnectorGenerator()->generateConnectorReferenceSetter($baseClass);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $referencedTableName
     *
     * @return string
     */
    public function generateSegmentConnectorReferenceColumnName(string $referencedTableName): string
    {
        return $this->getFactory()->createSegmentConnectorGenerator()->generateConnectorReferenceColumnName($referencedTableName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $referencedTableName
     * @param string $referencedColumnName
     *
     * @return string
     */
    public function generateSegmentConnectorTableUniqueConstraintName(string $referencedTableName, string $referencedColumnName): string
    {
        return $this->getFactory()
            ->createSegmentConnectorGenerator()
            ->generateSegmentConnectorTableUniqueConstraintName($referencedTableName, $referencedColumnName);
    }
}
