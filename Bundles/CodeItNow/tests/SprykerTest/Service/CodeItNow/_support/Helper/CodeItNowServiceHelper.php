<?php

namespace SprykerTest\Service\CodeItNow\Helper;

use Codeception\Module;
use Spryker\Service\CodeItNow\CodeItNowServiceInterface;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class CodeItNowServiceHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @return \Spryker\Service\CodeItNow\CodeItNowServiceInterface
     */
    public function getCodeItNowService(): CodeItNowServiceInterface
    {
        return $this->getLocator()
            ->codeItNow()
            ->service();
    }
}
