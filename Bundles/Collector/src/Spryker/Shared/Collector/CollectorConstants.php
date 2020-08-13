<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Collector;

use Spryker\Shared\Propel\PropelConstants;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
interface CollectorConstants
{
    /**
     * Specification:
     * - Used to configure the default document type for Elastica.
     *
     * @api
     */
    public const ELASTICA_PARAMETER__DOCUMENT_TYPE = 'ELASTICA_PARAMETER__DOCUMENT_TYPE';

    /**
     * Specification:
     * - Used to configure the default index name for Elastica.
     *
     * @api
     */
    public const ELASTICA_PARAMETER__INDEX_NAME = 'ELASTICA_PARAMETER__INDEX_NAME';

    /**
     * Specification:
     * - Used to configure which DB engine to use within the Collector using the DB engine reference constants.
     *
     * @api
     */
    public const ZED_DB_ENGINE = PropelConstants::ZED_DB_ENGINE;

    /**
     * Specification:
     * - DB engine reference to MySql.
     *
     * @deprecated Will be removed without replacement.
     *
     * @api
     */
    public const ZED_DB_ENGINE_MYSQL = 'ZED_DB_ENGINE_MYSQL';

    /**
     * Specification:
     * - DB engine reference to Pgsql.
     *
     * @deprecated Will be removed without replacement.
     *
     * @api
     */
    public const ZED_DB_ENGINE_PGSQL = 'ZED_DB_ENGINE_PGSQL';

    /**
     * Specification:
     * - Activate the deleted touch records cleanup
     *
     * @api
     */
    public const TOUCH_DELETE_CLEANUP_ACTIVE = 'COLLECTOR:TOUCH_DELETE_CLEANUP_ACTIVE';
}
