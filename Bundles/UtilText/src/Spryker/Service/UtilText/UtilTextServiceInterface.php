<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Service\UtilText;

interface UtilTextServiceInterface
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
    public function generateSlug($value);

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
    public function generateRandomString($length);
}
