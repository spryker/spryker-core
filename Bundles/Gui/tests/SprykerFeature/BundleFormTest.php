<?php

use Silex\WebTestCase;

class BundleFormTest extends WebTestCase
{
    public function createApplication()
    {
        define('APPLICATION_ENV', 'testing');
        define('APPLICATION_STORE', 'DE');
        define('APPLICATION_ROOT_DIR', '/data/shop/development/current' );
        $app = (new Pyz\Zed\Application\Communication\ZedBootstrap())->boot();
        $app['session.test'] = true;
        return $app;
    }

    public function testTrue()
    {
        $client = $this->createClient();
        $this->assertTrue(true);
    }

}
