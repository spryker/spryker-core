<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

use Codeception\Event\SuiteEvent;
use Codeception\Events;
use Codeception\Exception\ExtensionException;
use Codeception\Extension;
use Codeception\Module\WebDriver;

class WebDriverHelper extends Extension
{
    protected const MODULE_NAME_WEBDRIVER = 'WebDriver';

    protected const KEY_REMOTE_ENABLE = 'remote-enable';
    protected const KEY_HOST = 'host';
    protected const KEY_PORT = 'port';
    protected const KEY_BROWSER = 'browser';
    protected const KEY_CAPABILITIES = 'capabilities';

    protected const DEFAULT_HOST = '0.0.0.0';
    protected const DEFAULT_PORT = 4444;
    protected const DEFAULT_BROWSER = 'phantomjs';
    protected const DEFAULT_PATH = 'vendor/bin/phantomjs';
    protected const DEFAULT_TIMEOUT = 10;

    protected const BROWSER_PARAMETERS = [
        'vendor/bin/chromedriver' => [
            'webdriver-port' => '--port',
            'whitelisted-ips' => '--whitelisted-ips',
            'url-base' => '--url-base',
        ],
        'vendor/bin/phantomjs' => [
            'port' => '--webdriver',
            'proxy' => '--proxy',
            'proxyType' => '--proxy-type',
            'proxyAuth' => '--proxy-auth',
            'webSecurity' => '--web-security',
            'ignoreSslErrors' => '--ignore-ssl-errors',
            'sslProtocol' => '--ssl-protocol',
            'sslCertificatesPath' => '--ssl-certificates-path',
            'remoteDebuggerPort' => '--remote-debugger-port',
            'remoteDebuggerAutorun' => '--remote-debugger-autorun',
            'cookiesFile' => '--cookies-file',
            'diskCache' => '--disk-cache',
            'maxDiskCacheSize' => '--max-disk-cache-size',
            'loadImages' => '--load-images',
            'localStoragePath' => '--local-storage-path',
            'localStorageQuota' => '--local-storage-quota',
            'localToRemoteUrlAccess' => '--local-to-remote-url-access',
            'outputEncoding' => '--output-encoding',
            'scriptEncoding' => '--script-encoding',
            'webdriverLoglevel' => '--webdriver-loglevel',
            'webdriverLogfile' => '--webdriver-logfile',
        ],
    ];

    /**
     * @var string[]
     */
    public static $events = [
        Events::SUITE_INIT => 'suiteInit',
        Events::SUITE_BEFORE => 'configureWebDriverModule',
    ];

    /**
     * @var resource
     */
    protected $resource;

    /**
     * @var array
     */
    protected $pipes;

    /**
     * @param array $config
     * @param array $options
     */
    public function __construct(array $config, array $options)
    {
        if (isset($config['silent']) && $config['silent']) {
            $options['silent'] = true;
        }

        parent::__construct($config, $options);

        if (!isset($this->config['path'])) {
            $this->config['path'] = static::DEFAULT_PATH;
        }

        if (!isset($this->config['port'])) {
            $this->config['port'] = static::DEFAULT_PORT;
        }

        if (!isset($this->config['debug'])) {
            $this->config['debug'] = false;
        }
    }

    public function __destruct()
    {
        $this->stopServer();
    }

    /**
     * @param \Codeception\Event\SuiteEvent $e
     *
     * @throws \Codeception\Exception\ExtensionException
     *
     * @return void
     */
    public function suiteInit(SuiteEvent $e): void
    {
        if (!$this->isRemoteEnabled()) {
            if (!file_exists(realpath($this->config['path']))) {
                throw new ExtensionException($this, "Webdriver executable not found: {$this->config['path']}");
            }

            if (isset($this->config['suites'])) {
                if (is_string($this->config['suites'])) {
                    $suites = [$this->config['suites']];
                } else {
                    $suites = $this->config['suites'];
                }

                if (
                    !in_array($e->getSuite()->getBaseName(), $suites, true)
                    && !in_array($e->getSuite()->getName(), $suites, true)
                ) {
                    return;
                }
            }

            $this->startServer();
        }
    }

    /**
     * @return void
     */
    public function configureWebDriverModule(): void
    {
        if (!$this->hasModule(static::MODULE_NAME_WEBDRIVER)) {
            return;
        }

        $this->getWebDriver()->_reconfigure(
            $this->getWebDriverConfig()
        );
    }

    /**
     * @throws \Codeception\Exception\ExtensionException
     *
     * @return void
     */
    protected function startServer(): void
    {
        if ($this->resource !== null) {
            return;
        }

        $this->writeln(PHP_EOL);
        $this->writeln('Starting webdriver server.');

        $command = $this->getCommand();

        if ($this->config['debug']) {
            $this->writeln(PHP_EOL);

            $this->writeln('Generated webdriver command:');
            $this->writeln($command);
            $this->writeln(PHP_EOL);
        }

        $descriptorSpec = [
            ['pipe', 'r'],
            ['file', $this->getLogDir() . 'webdriver.output.txt', 'w'],
            ['file', $this->getLogDir() . 'webdriver.errors.txt', 'a'],
        ];

        $this->resource = proc_open(
            $command,
            $descriptorSpec,
            $this->pipes,
            null,
            null,
            ['bypass_shell' => true]
        );

        // phpcs:disable
        if (!is_resource($this->resource) || !proc_get_status($this->resource)['running']) {
            // phpcs:enable
            proc_close($this->resource);

            throw new ExtensionException($this, 'Failed to start webdriver server.');
        }

        $max_checks = 10;
        $checks = 0;

        $this->write('Waiting for the webdriver server to be reachable.');

        while (true) {
            if ($checks >= $max_checks) {
                throw new ExtensionException($this, 'Webdriver server never became reachable.');
            }

            // phpcs:disable
            $fp = @fsockopen(
                '127.0.0.1',
                $this->config['port'],
                $errCode,
                $errStr,
                static::DEFAULT_TIMEOUT
            );
            // phpcs:enable
            if ($fp) {
                $this->writeln('');
                $this->writeln('Webdriver server now accessible.');
                fclose($fp);

                break;
            }

            $this->write('.');
            $checks++;
            sleep(1);
        }

        $this->writeln('');
    }

    /**
     * @return void
     */
    protected function stopServer(): void
    {
        if ($this->resource !== null) {
            $this->write('Stopping webdriver server.');
            $max_checks = 10;

            for ($i = 0; $i < $max_checks; $i++) {
                if ($i === $max_checks - 1 && proc_get_status($this->resource)['running'] === true) {
                    $this->writeln('');
                    $this->writeln('Unable to properly shutdown webdriver server.');
                    unset($this->resource);

                    break;
                }

                if (proc_get_status($this->resource)['running'] === false) {
                    $this->writeln('');
                    $this->writeln('Webdriver server stopped.');
                    unset($this->resource);

                    break;
                }

                foreach ($this->pipes as $pipe) {
                    // phpcs:disable
                    if (is_resource($pipe)) {
                        fclose($pipe);
                    }
                    // phpcs:enable
                }

                proc_terminate($this->resource, 2);
                $this->write('.');
                sleep(1);
            }
        }
    }

    /**
     * @throws \Codeception\Exception\ExtensionException
     *
     * @return string[]
     */
    protected function getCommandParametersMapping()
    {
        $browser_path = $this->config['path'];

        if (!empty(static::BROWSER_PARAMETERS[$browser_path])) {
            return static::BROWSER_PARAMETERS[$browser_path];
        }

        throw new ExtensionException($this, 'Unknown browser specified: ' . $browser_path);
    }

    /**
     * @return string
     */
    protected function getCommandParameters(): string
    {
        $mapping = $this->getCommandParametersMapping();
        $params = [];

        foreach ($this->config as $configKey => $configValue) {
            if (!empty($mapping[$configKey])) {
                if (is_bool($configValue)) {
                    $configValue = $configValue ? 'true' : 'false';
                }
                $params[] = $mapping[$configKey] . '=' . $configValue;
            }
        }

        return implode(' ', $params);
    }

    /**
     * @return string
     */
    protected function getCommand(): string
    {
        return 'exec ' . escapeshellarg(realpath($this->config['path'])) . ' ' . $this->getCommandParameters();
    }

    /**
     * @return string[]
     */
    protected function getWebDriverConfig(): array
    {
        $webdriverConfig = [];

        $webdriverConfig[static::KEY_HOST] = $this->config[static::KEY_HOST] ?? static::DEFAULT_HOST;
        $webdriverConfig[static::KEY_PORT] = $this->config[static::KEY_PORT] ?? static::DEFAULT_PORT;
        $webdriverConfig[static::KEY_BROWSER] = $this->config[static::KEY_BROWSER] ?? static::DEFAULT_BROWSER;
        $webdriverConfig[static::KEY_CAPABILITIES] = $this->config[static::KEY_CAPABILITIES] ?? [];

        return $webdriverConfig;
    }

    /**
     * @return \Codeception\Module\WebDriver
     */
    protected function getWebDriver(): WebDriver
    {
        return $this->getModule(static::MODULE_NAME_WEBDRIVER);
    }

    /**
     * @return bool
     */
    protected function isRemoteEnabled(): bool
    {
        $isRemoteEnabled = $this->config[static::KEY_REMOTE_ENABLE] ?? false;

        if ($isRemoteEnabled === 'false') {
            return false;
        }

        return (bool)$isRemoteEnabled;
    }
}
