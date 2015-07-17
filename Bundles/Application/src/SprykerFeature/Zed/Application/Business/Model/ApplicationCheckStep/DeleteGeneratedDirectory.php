<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Business\Model\ApplicationCheckStep;

use Symfony\Component\Filesystem\Filesystem;

class DeleteGeneratedDirectory extends AbstractApplicationCheckStep
{

    /**
     * @return bool
     */
    public function run()
    {
        $dir = APPLICATION_SOURCE_DIR . DIRECTORY_SEPARATOR . 'Generated';
        $this->info('Delete "' . $dir . '" directory');

        $filesystem = new Filesystem();
        $filesystem->remove($dir);
    }

}
