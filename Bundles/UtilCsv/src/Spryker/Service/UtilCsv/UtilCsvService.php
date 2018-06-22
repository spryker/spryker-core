<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilCsv;

use SplFileObject;
use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\UtilCsv\UtilCsvServiceFactory getFactory()
 */
class UtilCsvService extends AbstractService implements UtilCsvServiceInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \SplFileObject $file
     *
     * @return array
     */
    public function readFile(SplFileObject $file): array
    {
        return $this->getFactory()
            ->createFileReader()
            ->readFile($file);
    }
}
