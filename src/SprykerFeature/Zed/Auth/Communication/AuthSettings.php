<?php

namespace SprykerFeature\Zed\Auth\Communication;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\Locator;

class AuthSettings
{
    /**
     * @var AutoCompletion
     */
    protected $locator;

    /**
     * @param Locator $locator
     */
    public function __construct(Locator $locator)
    {
        $this->locator = $locator;
    }

    public function getLoginPageUrl()
    {
        return '/auth/login';
    }
}
