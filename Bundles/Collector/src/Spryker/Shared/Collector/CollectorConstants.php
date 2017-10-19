<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Collector;

use Spryker\Shared\Propel\PropelConstants;

interface CollectorConstants
{
    const ELASTICA_PARAMETER__DOCUMENT_TYPE = 'ELASTICA_PARAMETER__DOCUMENT_TYPE';
    const ELASTICA_PARAMETER__INDEX_NAME = 'ELASTICA_PARAMETER__INDEX_NAME';

    const ZED_DB_ENGINE = PropelConstants::ZED_DB_ENGINE;
    const ZED_DB_ENGINE_MYSQL = PropelConstants::ZED_DB_ENGINE_MYSQL;
    const ZED_DB_ENGINE_PGSQL = PropelConstants::ZED_DB_ENGINE_PGSQL;
}
