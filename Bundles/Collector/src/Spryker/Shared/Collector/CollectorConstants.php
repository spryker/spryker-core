<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Collector;

use Spryker\Shared\Propel\PropelConstants;

interface CollectorConstants
{
    /**
     * Specification:
     * - Used to configure the default document type for Elastica.
     *
     * @api
     */
    const ELASTICA_PARAMETER__DOCUMENT_TYPE = 'ELASTICA_PARAMETER__DOCUMENT_TYPE';

    /**
     * Specification:
     * - Used to configure the default index name for Elastica.
     *
     * @api
     */
    const ELASTICA_PARAMETER__INDEX_NAME = 'ELASTICA_PARAMETER__INDEX_NAME';

    /**
     * Specification:
     * - Used to configure which DB engine to use within the Collector using the DB engine reference constants.
     *
     * @api
     */
    const ZED_DB_ENGINE = PropelConstants::ZED_DB_ENGINE;

    /**
     * Specification:
     * - DB engine reference to MySql.
     *
     * @api
     */
    const ZED_DB_ENGINE_MYSQL = PropelConstants::ZED_DB_ENGINE_MYSQL;

    /**
     * Specification:
     * - DB engine reference to Pgsql.
     *
     * @api
     */
    const ZED_DB_ENGINE_PGSQL = PropelConstants::ZED_DB_ENGINE_PGSQL;

    /**
     * Specification:
     * - Activate the deleted touch records cleanup
     *
     * @api
     */
    const TOUCH_DELETE_CLEANUP_ACTIVE = 'COLLECTOR:TOUCH_DELETE_CLEANUP_ACTIVE';
}
