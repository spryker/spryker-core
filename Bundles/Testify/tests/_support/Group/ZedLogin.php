<?php

namespace Testify\Group;

use Codeception\Event\TestEvent;
use Codeception\GroupObject;

class ZedLogin extends GroupObject
{

    public static $group = 'ZedLogin';

    /**
     * @param \Codeception\Event\TestEvent $e
     *
     * @return void
     */
    public function _before(TestEvent $e)
    {
        $this->getZedModule()->amZed();
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
