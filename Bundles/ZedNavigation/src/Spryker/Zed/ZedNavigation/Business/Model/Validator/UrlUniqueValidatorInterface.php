<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedNavigation\Business\Model\Validator;

interface UrlUniqueValidatorInterface
{

    /**
     * @param string $url
     *
     * @throws \Exception
     *
     * @return void
     */
    public function validate($url);

    /**
     * @param string $url
     *
     * @return void
     */
    public function addUrl($url);

}
