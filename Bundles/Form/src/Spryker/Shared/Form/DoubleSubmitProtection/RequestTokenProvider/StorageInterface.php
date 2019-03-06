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
     * @return string
     */
    public function getToken(string $formName): string;

    /**
     * @param string $formName
     *
     * @return void
     */
    public function deleteToken(string $formName): void;

    /**
     * @param string $formName
     * @param string $token
     *
     * @return void
     */
    public function setToken(string $formName, string $token): void;
}
