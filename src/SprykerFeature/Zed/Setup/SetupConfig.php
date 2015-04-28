<?php

namespace SprykerFeature\Zed\Setup\Business;

use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

class SetupConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getPathForJobsPHP()
    {
        return implode(DIRECTORY_SEPARATOR, [
            APPLICATION_ROOT_DIR,
            'config',
            'Zed',
            'cronjobs',
            'jobs.php'
        ]);
    }

}
