<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace ApiQueryBuilder\Module;

use Codeception\Module;
use Codeception\TestInterface;
use Silex\Application;
use Spryker\Zed\Propel\Communication\Plugin\ServiceProvider\PropelServiceProvider;

class ApiQueryBuilder extends Module
{

    /**
     * @param array|null $config
     */
    public function __construct($config = null)
    {
        parent::__construct($config);

        $propelServiceProvider = new PropelServiceProvider();
        $propelServiceProvider->boot(new Application());
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test)
    {
        parent::_before($test);

        $this->cleanUpDatabase();
    }

    /**
     * @return void
     */
    public function _afterSuite()
    {
        parent::_afterSuite();

        $this->cleanUpDatabase();
    }

    /**
     * @param \Codeception\TestInterface $test
     * @param bool $fail
     *
     * @return void
     */
    public function _failed(TestInterface $test, $fail)
    {
        parent::_failed($test, $fail);

        $this->cleanUpDatabase();
    }

    /**
     * @return void
     */
    private function cleanUpDatabase()
    {
    }

}
