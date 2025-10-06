<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\ConfigReader;

use Spryker\Zed\Propel\PropelConfig;

class PropelConfigReader implements PropelConfigReaderInterface
{
    public function __construct(protected PropelConfig $propelConfig)
    {
    }

    public function getSchemaDirectory(): string
    {
        return $this->propelConfig->getSchemaDirectory();
    }

    public function isCollationCaseSensitive(): bool
    {
        /** @var bool|null $isCollationCaseSensitive */
        static $isCollationCaseSensitive = null;
        if ($isCollationCaseSensitive !== null) {
            return $isCollationCaseSensitive;
        }

        if ($this->propelConfig->getCurrentDatabaseEngine() === PropelConfig::DB_ENGINE_PGSQL) {
            $isCollationCaseSensitive = true;

            return $isCollationCaseSensitive;
        }

        $connectionSettingsQueriesParam = $this->propelConfig->getPropelConfig()['database']['connections']['default']['settings']['queries'] ?? null;
        if ($connectionSettingsQueriesParam === null || !is_string($connectionSettingsQueriesParam)) {
            $isCollationCaseSensitive = false;

            return $isCollationCaseSensitive;
        }

        /** @var list<string> $connectionSettingsQueries */
        $connectionSettingsQueries = explode(', ', $connectionSettingsQueriesParam);
        if (count($connectionSettingsQueries) === 0) {
            $isCollationCaseSensitive = false;

            return $isCollationCaseSensitive;
        }

        foreach ($connectionSettingsQueries as $connectionSettingsQuery) {
            if (str_starts_with($connectionSettingsQuery, 'COLLATION_CONNECTION') && str_ends_with($connectionSettingsQuery, '_ci')) {
                $isCollationCaseSensitive = false;

                return $isCollationCaseSensitive;
            }
        }

        $isCollationCaseSensitive = true;

        return $isCollationCaseSensitive;
    }
}
