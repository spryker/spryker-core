<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Setup\Business\Model;

use ErrorException;
use Spryker\Shared\Config\Environment;
use Spryker\Zed\Setup\SetupConfig;

class Cronjobs
{
    public const ROLE_ADMIN = 'admin';
    public const ROLE_REPORTING = 'reporting';
    public const ROLE_EMPTY = 'empty';
    public const DEFAULT_ROLE = self::ROLE_ADMIN;
    public const DEFAULT_AMOUNT_OF_DAYS_FOR_LOGFILE_ROTATION = 7;
    public const JENKINS_API_JOBS_URL = 'api/json/jobs?pretty=true&tree=jobs[name]';

    /**
     * @var array
     */
    protected $allowedRoles = [
        self::ROLE_ADMIN,
        self::ROLE_REPORTING,
        self::ROLE_EMPTY,
    ];

    /**
     * @var \Spryker\Zed\Setup\SetupConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Setup\SetupConfig $config
     */
    public function __construct(SetupConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param array $roles
     *
     * @return string
     */
    public function generateCronjobs(array $roles)
    {
        if (empty($roles)) {
            $roles = [self::DEFAULT_ROLE];
        }

        $this->checkRoles($roles);

        $jobsByName = $this->getCronjobs($roles);

        $consoleOutput = '';
        $consoleOutput .= $this->updateOrDelete($jobsByName);
        $consoleOutput .= $this->createJobDefinitions($jobsByName);

        return $consoleOutput;
    }

    /**
     * @return string
     */
    public function disableJenkins()
    {
        $url = 'quietDown';
        $code = $this->callJenkins($url);

        return "Jenkins disabled (response: $code)\n";
    }

    /**
     * @return string
     */
    public function enableJenkins()
    {
        $url = 'cancelQuietDown';
        $code = $this->callJenkins($url);

        return "Jenkins enabled (response: $code)\n";
    }

    /**
     * @param array $roles
     *
     * @throws \ErrorException
     *
     * @return void
     */
    protected function checkRoles(array $roles)
    {
        foreach ($roles as $role) {
            if (!in_array($role, $this->allowedRoles)) {
                throw new ErrorException(
                    $role . ' is not in the list of allowed job roles! Cannot continue configuration of jenkins!'
                );
            }
        }
    }

    /**
     * @param array $roles
     *
     * @return array
     */
    protected function getCronjobs(array $roles)
    {
        $jobs = [];

        include_once $this->getJobConfigPath();

        foreach ($jobs as $i => $job) {
            if (!empty($job['command'])) {
                $command = $job['command'];
                $commandExpl = explode(' ', $command);
                $requestParts = ['module' => '', 'controller' => '', 'action' => ''];
                foreach ($commandExpl as $part) {
                    $segments = array_keys($requestParts);
                    foreach ($segments as $segment) {
                        if (strpos($part, $segment . '=') !== false) {
                            $requestParts[$segment] = str_replace('--' . $segment . '=', '', $part);
                        }
                    }
                }

                $jobs[$i]['request'] = '/' . $requestParts['module'] . '/' . $requestParts['controller']
                    . '/' . $requestParts['action'];

                $jobs[$i]['id'] = null;
            }
        }

        return $this->indexJobsByName($jobs, $roles);
    }

    /**
     * @param array $jobs
     * @param array $roles
     *
     * @return array
     */
    protected function indexJobsByName(array $jobs, array $roles)
    {
        $jobsByName = [];

        foreach ($jobs as $v) {
            if (array_key_exists('role', $v) && in_array($v['role'], $this->allowedRoles)) {
                $jobRole = $v['role'];
            } else {
                $jobRole = self::DEFAULT_ROLE;
            }

            // Enable jobs only for roles matching those specified via command line argument
            if (array_search($jobRole, $roles) === false) {
                continue;
            }

            foreach ($v['stores'] as $store) {
                $name = $store . '__' . $v['name'];
                $jobsByName[$name] = $v;
                $jobsByName[$name]['name'] = $name;
                $jobsByName[$name]['store'] = $store;
                $jobsByName[$name]['role'] = $jobRole;
                unset($jobsByName[$name]['stores']);
            }
        }

        return $jobsByName;
    }

    /**
     * @return array
     */
    protected function getExistingJobs()
    {
        $jobsNames = [];

        $jobs = $this->getJenkinsApiResponse(self::JENKINS_API_JOBS_URL);
        $jobs = json_decode($jobs, true);

        if (!empty($jobs['jobs'])) {
            foreach ($jobs['jobs'] as $job) {
                $jobsNames[] = $job['name'];
            }
        }

        return $jobsNames;
    }

    /**
     * Loop over existing jobs: either update or delete job
     *
     * @param array $jobsByName
     *
     * @return string
     */
    protected function updateOrDelete(array $jobsByName)
    {
        $output = '';
        $existingJobs = $this->getExistingJobs();

        if (empty($existingJobs)) {
            return $output;
        }

        foreach ($existingJobs as $name) {
            if (!in_array($name, array_keys($jobsByName))) {
                // Job does not exist anymore - we have to delete it.
                $url = 'job/' . $name . '/doDelete';
                $code = $this->callJenkins($url);
                $output .= "DELETE  jenkins job: $url (http_response: $code)" . PHP_EOL;
            } else {
                // Job exists - let's update config.xml and remove it from array of jobs
                $xml = $this->prepareJobXml($jobsByName[$name]);
                $url = 'job/' . $name . '/config.xml';
                $code = $this->callJenkins($url, $xml);

                if ($code !== 200) {
                    $output .= "UPDATE jenkins job: $url (http_response: $code)" . PHP_EOL;
                }
            }
        }

        return $output;
    }

    /**
     * Create Jenkins jobs for provided list of jobs
     *
     * @param array $jobsByName
     *
     * @return string
     */
    protected function createJobDefinitions(array $jobsByName)
    {
        $output = '';

        $existingJobs = $this->getExistingJobs();

        foreach ($jobsByName as $k => $v) {
            // skip if job is in existingjobs
            if (in_array($k, $existingJobs)) {
                $output .= "SKIPPED jenkins job: $k (already exists)" . PHP_EOL;
                continue;
            }

            $url = 'createItem?name=' . $v['name'];

            $xml = $this->prepareJobXml($v);
            $code = $this->callJenkins($url, $xml);

            if ($code === 400) {
                $code = '400: already exists';
            }
            $output .= "CREATE jenkins job: $url (http_response: $code)" . PHP_EOL;
        }

        return $output;
    }

    /**
     * @param string $url
     * @param string $body
     *
     * @throws \ErrorException
     *
     * @return int
     */
    protected function callJenkins($url, $body = '')
    {
        $postUrl = $this->getJenkinsUrl($url);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $postUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: text/xml']);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $curlResponse = curl_exec($ch);

        if ($curlResponse === false) {
            throw new ErrorException('cURL error: ' . curl_error($ch) . ' while calling Jenkins URL ' . $postUrl);
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return (int)$httpCode;
    }

    /**
     * @param string $url
     *
     * @throws \ErrorException
     *
     * @return string
     */
    protected function getJenkinsApiResponse($url)
    {
        $getUrl = $this->getJenkinsUrl($url);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $getUrl);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);

        $curlResponse = curl_exec($ch);

        if ($curlResponse === false) {
            throw new ErrorException('cURL error: ' . curl_error($ch) . ' while calling Jenkins URL ' . $getUrl);
        }
        curl_close($ch);

        return $curlResponse;
    }

    /**
     * Render Job description (as XML) for Jenkins API call
     *
     * @todo Move XML snippet to twig template
     *
     * @param array $job
     *
     * @return string
     */
    protected function prepareJobXml(array $job)
    {
        $disabled = ($job['enable'] === true) ? 'false' : 'true';
        $schedule = $this->getSchedule($job);
        $daysToKeep = $this->getDaysToKeep($job);
        $command = htmlspecialchars($job['command']);
        $store = $job['store'];

        $xml = "<?xml version='1.0' encoding='UTF-8'?>
<project>
  <actions/>
  <description></description>
  <logRotator>
    <daysToKeep>$daysToKeep</daysToKeep>
    <numToKeep>-1</numToKeep>
    <artifactDaysToKeep>$daysToKeep</artifactDaysToKeep>
    <artifactNumToKeep>-1</artifactNumToKeep>
  </logRotator>
  <keepDependencies>false</keepDependencies>
  <properties/>
  <scm class='hudson.scm.NullSCM'/>
  <canRoam>true</canRoam>
  <disabled>$disabled</disabled>
  <blockBuildWhenDownstreamBuilding>false</blockBuildWhenDownstreamBuilding>
  <blockBuildWhenUpstreamBuilding>false</blockBuildWhenUpstreamBuilding>
  <triggers class='vector'>$schedule</triggers>
  <concurrentBuild>false</concurrentBuild>
  <builders>
    <hudson.tasks.Shell>";

        $xml .= $this->getCommand($command, $store);
        $xml .= "
    </hudson.tasks.Shell>
  </builders>\n"
            . $this->getPublisherString($job) . "\n
  <buildWrappers/>
</project>\n";

        return $xml;
    }

   /**
    * Render partial for job description with publisher settings
    * it returns not empty XML entity if job has email notifications set.
    *
    * @param array $job
    *
    * @return string
    */
    protected function getPublisherString(array $job)
    {
        if (empty($job['notifications']) || !is_array($job['notifications'])) {
            return '<publishers/>';
        }

        $recipients = implode(' ', $job['notifications']);

        return "<publishers>
    <hudson.tasks.Mailer>
      <recipients>$recipients</recipients>
      <dontNotifyEveryUnstableBuild>false</dontNotifyEveryUnstableBuild>
      <sendToIndividuals>false</sendToIndividuals>
    </hudson.tasks.Mailer>
</publishers>";
    }

    /**
     * Gets a string with job schedule (how often run job). The schedule string is compatible
     * with cronjob schedule defininion (eg. 0 * * * * meaning: run once each hour at 00 minute).
     * If environment is development, return empty string - we execute cronjobs on development environment
     * only manually.
     *
     * @param array $job
     *
     * @return string
     */
    protected function getSchedule(array $job)
    {
        $schedule = ($job['schedule'] === '') ? '' : ' <hudson.triggers.TimerTrigger><spec>' . $job['schedule'] . '</spec></hudson.triggers.TimerTrigger>';

        if (array_key_exists('run_on_non_production', $job) && $job['run_on_non_production'] === true) {
            return $schedule;
        }

        if (Environment::isNotProduction()) {
            // Non-production - don't run automatically via Jenkins
            return '';
        }

        return $schedule;
    }

    /**
     * Get number of days to keep job output history. Each run is a directory, so we definitely need to keep it clean.
     *
     * @param array $job
     *
     * @return int
     */
    protected function getDaysToKeep(array $job)
    {
        if (array_key_exists('logrotate_days', $job) && is_int($job['logrotate_days'])) {
            return $job['logrotate_days'];
        }

        return self::DEFAULT_AMOUNT_OF_DAYS_FOR_LOGFILE_ROTATION;
    }

    /**
     * @param string $command
     * @param string $store
     *
     * @return string
     */
    protected function getCommand($command, $store)
    {
        $environment = Environment::getInstance();
        $environment_name = $environment->getEnvironment();
        if ($environment->isNotDevelopment()) {
            return "<command>[ -f " . APPLICATION_ROOT_DIR . "/deploy/vars ] &amp;&amp; . " . APPLICATION_ROOT_DIR . "/deploy/vars
export APPLICATION_ENV=$environment_name
export APPLICATION_STORE=$store
cd \$destination_release_dir
. ./config/Zed/cronjobs/cron.conf
$command</command>";
        }

        return "<command>
export APPLICATION_ENV=$environment_name
export APPLICATION_STORE=$store
cd /data/shop/development/current
. ./config/Zed/cronjobs/cron.conf
$command</command>";
    }

    /**
     * @return string
     */
    protected function getJobConfigPath()
    {
        return $this->config->getPathForJobsPHP();
    }

    /**
     * @param string $location
     *
     * @return string
     */
    protected function getJenkinsUrl($location)
    {
        return $this->config->getJenkinsUrl() . $location;
    }

    /**
     * @return string
     */
    protected function getJobsDir()
    {
        return $this->config->getJenkinsJobsDirectory();
    }
}
