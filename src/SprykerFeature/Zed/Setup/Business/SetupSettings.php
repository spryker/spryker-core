<?php

namespace SprykerFeature\Zed\Setup\Business;



class SetupSettings
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
