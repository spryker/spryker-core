<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Setup\Communication\Controller;

use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\Library\DataDirectory;
use SprykerFeature\Shared\Library\Environment;
use SprykerFeature\Shared\Library\Log;
use SprykerFeature\Shared\Setup\SetupConfig;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;

/**
 * @deprecated
 */
class JenkinsController extends AbstractController
{

    const LOGFILE = 'jenkins.log';
    const ROLE_ADMIN = 'admin';
    const ROLE_REPORTING = 'reporting';
    const ROLE_EMPTY = 'empty';
    const DEFAULT_ROLE = self::ROLE_ADMIN;
    const DEFAULT_AMOUNT_OF_DAYS_FOR_LOGFILE_ROTATION = 7;

    /**
     * @var array
     */
    protected $allowedRoles = [
        self::ROLE_ADMIN,
        self::ROLE_REPORTING,
        self::ROLE_EMPTY,
    ];

    public function init()
    {
    }

    /**
     * @param string $url
     * @param string $body
     *
     * @return mixed
     */
    private function callJenkins($url, $body = '')
    {
        $post_url = Config::get(SetupConfig::JENKINS_BASE_URL) . '/' . $url;//createItem?name=" . $v['name'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $post_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: text/xml']);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        Log::logRaw('CURL call: ' . $post_url . "body:\n[" . $body . "]\n\n", self::LOGFILE);
        $head = curl_exec($ch);
        Log::logRaw("CURL response:\n[" . $head . "]\n\n", self::LOGFILE);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $httpCode;
    }

    /**
     * @param array $job
     *
     * @return string
     */
    private function prepareJobXml($job)
    {
        $disabled = (true === $job['enable']) ? 'false' : 'true';
        $schedule = $this->getSchedule($job);
        $daysToKeep = $this->getDaysToKeep($job);
        $command = $job['command'];
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

    protected function getSchedule(array $job)
    {
        $schedule = ('' === $job['schedule']) ? '' : ' <hudson.triggers.TimerTrigger><spec>' . $job['schedule'] . '</spec></hudson.triggers.TimerTrigger>';

        if (array_key_exists('run_on_non_production', $job) && $job['run_on_non_production'] === true) {
            return $schedule;
        }

        if (Environment::isNotProduction()) {
            // Non-production - don't run automatically via Jenkins
            return '';
        } else {
            return $schedule;
        }
    }

    /**
     * @param array $job
     *
     * @return int
     */
    protected function getDaysToKeep(array $job)
    {
        if (array_key_exists('logrotate_days', $job) && is_int($job['logrotate_days'])) {
            return $job['logrotate_days'];
        } else {
            return self::DEFAULT_AMOUNT_OF_DAYS_FOR_LOGFILE_ROTATION;
        }
    }

    /**
     * @param string $command
     * @param string $store
     *
     * @return string
     */
    protected function getCommand($command, $store)
    {
        if (Environment::getInstance()->isNotDevelopment()) {
            return "<command>[ -f ../../../../../../../current/deploy/vars ] &amp;&amp; . ../../../../../../../current/deploy/vars
[ -f ../../../../../../current/deploy/vars ] &amp;&amp; . ../../../../../../current/deploy/vars
[ -f ../../../../../current/deploy/vars ] &amp;&amp; . ../../../../../current/deploy/vars
export APPLICATION_ENV=\$environment
export APPLICATION_STORE=$store
cd \$destination_release_dir/config/Zed/cronjobs
. ./cron.conf
$command</command>";
        } else {
            return "<command>
export APPLICATION_ENV=\$environment
export APPLICATION_STORE=$store
cd /data/shop/development/current/config/Zed/cronjobs
. ./cron.conf
$command</command>";
        }
    }

    public function generateAction()
    {
        require implode(
            DIRECTORY_SEPARATOR,
            [
                APPLICATION_ROOT_DIR,
                'config',
                'Zed',
                'cronjobs',
                'jobs.php',
            ]
        );

        $jobs_dir = DataDirectory::getLocalCommonPath('/jenkins/jobs/');

        $roles = $this->getRoles();

        if (false === $roles) {
            $roles = [self::DEFAULT_ROLE];
        }

        foreach ($roles as $role) {
            if (!in_array($role, $this->allowedRoles)) {
                throw new \ErrorException($role . ' is not in the list of allowed roles! Cannot continue configuration of jenkins!');
            }
        }

        $job_by_name = [];

        foreach ($jobs as $v) {
            if (array_key_exists('role', $v) && in_array($v['role'], $this->allowedRoles)) {
                $jobRole = $v['role'];
            } else {
                $jobRole = self::DEFAULT_ROLE;
            }

            // Enable jobs only for roles matching those specified via command line argument
            if (false === array_search($jobRole, $roles)) {
                continue;
            }

            foreach ($v['stores'] as $store) {
                $name = $store . '__' . $v['name'];
                $job_by_name[$name] = $v;
                $job_by_name[$name]['name'] = $name;
                $job_by_name[$name]['store'] = $store;
                $job_by_name[$name]['role'] = $jobRole;
                unset($job_by_name[$name]['stores']);
            }
        }

        // Loop thru existing jobs - either update them or delete them.
        $existing_jobs = glob($jobs_dir . '*/config.xml');
        if (!empty($existing_jobs)) {
            foreach ($existing_jobs as $v) {
                $name = basename(dirname($v));

                if (false === array_search($name, array_keys($job_by_name))) {
                    // Job does not exist anymore - we have to delete it.
                    $url = 'job/' . $name . '/doDelete';
                    $code = $this->callJenkins($url);
                    echo "Delete job: $url returned code $code\n";
                } else {
                    // Job exists - let's update config.xml and remove it from array of jobs
                    $xml = $this->prepareJobXml($job_by_name[$name]);
                    $url = 'job/' . $name . '/config.xml';
                    $code = $this->callJenkins($url, $xml);
                    unset($job_by_name[$name]);

                    if ($code !== '200') {
                        echo "Update: $url returned code $code\n";
                    }
                }
            }
        }

        // Create new job definitions
        foreach ($job_by_name as $k => $v) {
            $xml = $this->prepareJobXml($v);
            $url = 'createItem?name=' . $v['name'];
            $code = $this->callJenkins($url, $xml);
            echo "Jenkins API $url returned response: $code\n";
        }
    }

    public function reloadAction()
    {
        $url = 'reload';
        $code = $this->callJenkins($url);
        echo "Jenkins reloaded (response: $code)\n";
    }

    public function disableAction()
    {
        $url = 'quietDown';
        $code = $this->callJenkins($url);
        echo "Jenkins disabled (response: $code)\n";
    }

    public function enableAction()
    {
        $url = 'cancelQuietDown';
        $code = $this->callJenkins($url);
        echo "Jenkins enabled (response: $code)\n";
    }

    /**
     * @return bool|array
     */
    protected function getRoles()
    {
        if (php_sapi_name() !== 'cli') {
            return false;
        }

        $shortopts = 'r::';
        $longopts = [
            'role::',
        ];

        $options = getopt($shortopts, $longopts);
        if (array_key_exists('role', $options)) {
            return explode(',', $options['role']);
        } else {
            return false;
        }
    }

    /**
     * @param array $job
     *
     * @return string
     */
    protected function getPublisherString($job)
    {
        if (array_key_exists('notifications', $job) && is_array($job['notifications']) && !empty($job['notifications'])) {
            $recipients = implode(' ', $job['notifications']);

            return "<publishers>
                        <hudson.tasks.Mailer>
                          <recipients>$recipients</recipients>
                          <dontNotifyEveryUnstableBuild>false</dontNotifyEveryUnstableBuild>
                          <sendToIndividuals>false</sendToIndividuals>
                        </hudson.tasks.Mailer>
                    </publishers>";
        } else {
            return '<publishers/>';
        }
    }

}
