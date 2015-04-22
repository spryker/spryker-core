<?php

class SprykerFeature_Zed_Setup_Business_Model_Cronjobs
{


    /**
     * @return array
     */
    public function getCronjobs()
    {
        $jobs = array();

        $settings = $this->factory->createSettings();
        $path = $settings->getPathForJobsPHP();
        include $path;

        foreach ($jobs as $i => $job) {
            if (!empty($job['command'])) {
                $command = $job['command'];
                $commandExpl = explode(' ', $command);
                $requestParts = array('module' => '', 'controller' => '', 'action' => '',);
                foreach ($commandExpl as $part) {

                    $segments = array_keys($requestParts);
                    foreach ($segments as $segment) {
                        if (strpos($part, $segment . '=') !== false) {
                            $requestParts[$segment] = str_replace('--' . $segment . '=', '', $part);
                        }
                    }
                }

                $name = $requestParts['module'] . ' ' . $requestParts['controller'] . ' ' . $requestParts['action'];
                $jobs[$i]['request'] = '/' . $requestParts['module'] . '/' . $requestParts['controller'] . '/' . $requestParts['action'];

                $jobs[$i]['id'] = null;
            }
        }
        return $jobs;
    }

}
