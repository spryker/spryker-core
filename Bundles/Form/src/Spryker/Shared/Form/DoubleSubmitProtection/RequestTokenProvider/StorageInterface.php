<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Form\DoubleSubmitProtection\RequestTokenProvider;

interface StorageInterface
{
    /**
     * @param string $formName
     *
     * @return bool
     */
    public function getToken($formName);

    /**
     * @param string $formName
     *
     * @return void
     */
    public function deleteToken($formName);

    /**
     * @param string $formName
     * @param string $token
     *
     * @return string
     */
    public function setToken($formName, $token);
}
