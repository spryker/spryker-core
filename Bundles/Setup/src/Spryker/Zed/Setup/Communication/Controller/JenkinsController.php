<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Setup\Communication\Controller;

use ErrorException;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Config\Environment;
use Spryker\Shared\Setup\SetupConstants;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @deprecated Will be removed in 1.0.0.
 */
class JenkinsController extends AbstractController
{
    public const LOGFILE = 'jenkins.log';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_REPORTING = 'reporting';
    public const ROLE_EMPTY = 'empty';
    public const DEFAULT_ROLE = self::ROLE_ADMIN;
    public const DEFAULT_AMOUNT_OF_DAYS_FOR_LOGFILE_ROTATION = 7;

    /**
     * @var array
     */
    protected $allowedRoles = [
        self::ROLE_ADMIN,
        self::ROLE_REPORTING,
        self::ROLE_EMPTY,
    ];

    /**
     * @return void
     */
    public function init()
    {
    }

    /**
     * @param string $url
     * @param string $body
     *
     * @throws \ErrorException
     *
     * @return string
     */
    private function callJenkins($url, $body = '')
    {
        $postUrl = Config::get(SetupConstants::JENKINS_BASE_URL) . '/' . $url;
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

        return $httpCode;
    }

    /**
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

    /**
     * @return void
     */
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
    }

    /**
     * @return void
     */
    public function reloadAction()
    {
        $url = 'reload';
        $code = $this->callJenkins($url);
        echo "Jenkins reloaded (response: $code)\n";
    }

    /**
     * @return void
     */
    public function disableAction()
    {
        $url = 'quietDown';
        $code = $this->callJenkins($url);
        echo "Jenkins disabled (response: $code)\n";
    }

    /**
     * @return void
     */
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
        if (PHP_SAPI !== 'cli' && PHP_SAPI !== 'phpdbg') {
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
