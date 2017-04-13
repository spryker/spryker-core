<?php

namespace SprykerTest\Zed\Sales\Presentation\Helper;

use Codeception\Module;
use Codeception\TestInterface;
use SprykerTest\Shared\Application\Helper\ZedHelper;

class PresentationHelper extends Module
{

    /**
     * @param \Codeception\TestInterface $e
     *
     * @return void
     */
    public function _before(TestInterface $e)
    {
        $this->getZedModule()->amZed();
        $this->getZedModule()->amLoggedInUser();
    }

    /**
     * @return \Codeception\Module|\SprykerTest\Shared\Application\Helper\ZedHelper
     */
    protected function getZedModule()
    {
        return $this->getModule('\\' . ZedHelper::class);
    }

}
