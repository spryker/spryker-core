<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Propel;

use Spryker\Shared\Application\ApplicationConstants;

interface PropelConstants
{

    const PROPEL = ApplicationConstants::PROPEL;
    const PROPEL_DEBUG = ApplicationConstants::PROPEL_DEBUG;

    const PROPEL_SHOW_EXTENDED_EXCEPTION = 'PROPEL_SHOW_EXTENDED_EXCEPTION';

    const ZED_DB_DATABASE = ApplicationConstants::ZED_DB_DATABASE;

    const ZED_DB_ENGINE = ApplicationConstants::ZED_DB_ENGINE;

    const ZED_DB_HOST = ApplicationConstants::ZED_DB_HOST;
    const ZED_DB_PASSWORD = ApplicationConstants::ZED_DB_PASSWORD;
    const ZED_DB_PORT = ApplicationConstants::ZED_DB_PORT;
    const ZED_DB_USERNAME = ApplicationConstants::ZED_DB_USERNAME;

    const ZED_DB_ENGINE_MYSQL = ApplicationConstants::ZED_DB_ENGINE_MYSQL;
    const ZED_DB_ENGINE_PGSQL = ApplicationConstants::ZED_DB_ENGINE_PGSQL;

    const ZED_DB_SUPPORTED_ENGINES = 'ZED_DB_SUPPORTED_ENGINES';

}
