<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Twig;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Twig\Model\Loader\FilesystemLoader;

class TwigFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Yves\Twig\Model\Loader\FilesystemLoader
     */
    public function createFilesystemLoader()
    {
        return new FilesystemLoader(
            $this->getConfig()->getTemplatePaths()
        );
    }

}
