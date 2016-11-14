<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilText;

use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\UtilText\UtilTextServiceFactory getFactory()
 */
class UtilTextService extends AbstractService implements UtilTextServiceInterface
{

    /**
     * Specification:
     * - Generate slug based on value
     *
     * @api
     *
     * @param string $value
     *
     * @return string
     */
    public function generateSlug($value)
    {
        return $this->getFactory()
            ->createTextSlug()
            ->generate($value);
    }

    /**
     *
     * Specification:
     * - Generates random string for given lenght value
     *
     * @api
     *
     * @param int $length
     *
     * @return string
     */
    public function generateRandomString($length)
    {
        return $this->getFactory()
            ->createStringGenerator()
            ->generateRandomString($length);
    }
}
