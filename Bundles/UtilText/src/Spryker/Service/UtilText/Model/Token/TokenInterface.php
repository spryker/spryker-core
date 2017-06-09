<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilText\Model\Token;

interface TokenInterface
{

    /**
     * @param string $rawToken
     * @param array $options
     *
     * @return string
     */
    public function generate($rawToken, array $options = []);

    /**
     * @param string $rawToken
     * @param string $token
     *
     * @return bool
     */
    public function check($rawToken, $token);

}
