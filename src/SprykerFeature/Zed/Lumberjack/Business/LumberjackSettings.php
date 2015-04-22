<?php

namespace SprykerFeature\Zed\Lumberjack\Business;

use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\Lumberjack\LumberjackConfig;

class LumberjackSettings
{

    /**
     * @return string
     */
    public function getSearchUrl()
    {
        return '_search';
    }

    /**
     * @param bool $includeWildcard
     * @param bool $includeDate
     * @return string
     */
    public function getIndexName($includeWildcard = false, $includeDate = false)
    {
        $config = Config::get(LumberjackConfig::LUMBERJACK);

        if ($includeDate) {
            $index = $config->elasticsearch->index
                     . '_' . \SprykerFeature_Shared_Library_Environment::getEnvironment();

            // Marek Obuchowicz <marek.obuchowicz@project-a.com> schrieb:
            // Generated_Yves_Zed should use UTC time for generating index name (currently uses local timezone)
            $index .= '_' . gmdate('Y-m-d');
        } else {
            $index = $config->elasticsearch->index
                     . '_' . \SprykerFeature_Shared_Library_Environment::getEnvironment();
        }

        if ($includeWildcard) {
            $index .= '_*';
        }

        return $index;
    }

    /**
     * @return array
     */
    public function getEntityBlacklist()
    {
        return [];
    }
}
