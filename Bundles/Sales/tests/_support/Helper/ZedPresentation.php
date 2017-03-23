<?php

namespace Sales\Helper;

use Codeception\Module;
use Codeception\TestCase;

class ZedPresentation extends Module
{

    /**
     * @param \Codeception\TestCase $e
     *
     * @return void
     */
    public function _before(TestCase $e)
    {
        $this->getZedModule()->amLoggedInUser();
    }

    /**
     * @return \Application\Module\Zed
     */
    protected function getZedModule()
    {
        return $this->getModule('\Application\Module\Zed');
    }

}
