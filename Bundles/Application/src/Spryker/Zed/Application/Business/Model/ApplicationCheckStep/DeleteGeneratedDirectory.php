<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Business\Model\ApplicationCheckStep;

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
