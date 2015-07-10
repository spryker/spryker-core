<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Lumberjack;

use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\Lumberjack\LumberjackConfig as LumberjackSharedConfig;
use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

class LumberjackConfig extends AbstractBundleConfig
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
     *
     * @return string
     */
    public function getIndexName($includeWildcard = false, $includeDate = false)
    {
        $config = Config::get(LumberjackSharedConfig::LUMBERJACK);

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
