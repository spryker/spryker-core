<?php

namespace SprykerFeature\Yves\FrontendExporter\Business\Creator;

use Silex\Application;

interface ResourceCreatorInterface
{
    /**
     * @return string
     */
    public function getType();

    /**
     * @param Application $app
     * @param $data
     * @return array
     */
    public function createResource(Application $app, $data);
}