<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Setup\Communication\Controller;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Config\Environment;
use Spryker\Zed\Application\Communication\Controller\AbstractController;

class IndexController extends AbstractController
{

    const COOKIE_HASH_ALGORITHM = 'sha256';

    /**
     * @return array
     */
    public function indexAction()
    {
        $developmentLinks = [];

        if (APPLICATION_ENV !== 'production') {
            $developmentLinks[] = [
                'href' => '/setup/transfer/repeat',
                'target' => '_blank',
                'label' => 'Repeat last Yves-request',
            ];
            $developmentLinks[] = [
                'href' => '/glossary/dump',
                'target' => '_blank',
                'label' => 'Dump glossary data to file',
            ];
        }
        $developmentLinks[] = [
            'href' => '/setup/phpinfo',
            'target' => '_blank',
            'label' => 'Show PHP-Info',
        ];
        if (Environment::isNotDevelopment()) {
            $developmentLinks[] = [
                'href' => '#',
                'label' => 'Show Elasticsearch' . ' <span class="icon-info"></span>',
                'extras' => [
                    'data-toggle' => 'modal',
                    'data-target' => '#elastic',
                ],
            ];
        } else {
            $developmentLinks[] = [
                'href' => 'http://' . Config::get(ApplicationConstants::HOST_ZED_GUI) . ':9200',
                'target' => '_blank',
                'label' => 'Show Elasticsearch',
            ];
        }
        if (Environment::isNotDevelopment()) {
            $developmentLinks[] = [
                'href' => '#',
                'label' => 'Show Elasticsearch Head (9200/_plugin/head)' . ' <span class="icon-info"></span>',
                'extras' => [
                    'data-toggle' => 'modal',
                    'data-target' => '#elasticHead',
                ],
            ];
        } else {
            $developmentLinks[] = [
                'href' => 'http://' . Config::get(ApplicationConstants::HOST_ZED_GUI) . ':9200/_plugin/head',
                'target' => '_blank',
                'label' => 'Show Elasticsearch Head',
            ];
        }

        if (Environment::isNotDevelopment()) {
            $developmentLinks[] = [
                'href' => '#',
                'label' => 'Show Elasticsearch Bigdesk (9200/_plugin/bigdesk)' . ' <span class="icon-info"></span>',
                'extras' => [
                    'data-toggle' => 'modal',
                    'data-target' => '#elasticBigdeskModal',
                ],
            ];
        } else {
            $developmentLinks[] = [
                'href' => 'http://' . Config::get(ApplicationConstants::HOST_ZED_GUI) . ':9200/_plugin/bigdesk',
                'target' => '_blank',
                'label' => 'Show Elasticsearch Bigdesk',
            ];
        }
        if (Environment::isNotDevelopment()) {
            $developmentLinks[] = [
                'href' => '#',
                'label' => 'Show Couchbase' . ' <span class="icon-info"></span>',
                'extras' => [
                    'data-toggle' => 'modal',
                    'data-target' => '#couchbaseModal',
                ],
            ];
        } else {
            $developmentLinks[] = [
                'href' => 'http://' . Config::get(ApplicationConstants::HOST_ZED_GUI) . ':8091',
                'target' => '_blank',
                'label' => 'Show Couchbase',
            ];
        }

        if (Environment::isNotDevelopment()) {
            $developmentLinks[] = [
                'href' => '#',
                'label' => 'Show Jenkins' . ' <span class="icon-info"></span>',
                'extras' => [
                    'data-toggle' => 'modal',
                    'data-target' => '#jenkinsModal',
                ],
            ];
        } else {
            $developmentLinks[] = [
                'href' => Config::get(ApplicationConstants::JENKINS_BASE_URL),
                'target' => '_blank',
                'label' => 'Jenkins',
            ];
        }

        $developmentLinks[] = [
            'href' => 'URL IS MISSING',
            'target' => '_blank',
            'label' => 'Install / Update Cronjobs',
        ];

        return $this->viewResponse([
            'developmentLinks' => $developmentLinks,
        ]);
    }

    /**
     * @return array
     */
    public function showCronjobsAction()
    {
        return $this->viewResponse([
            'jobs' => $this->facadeSetup->getAllCronjobs(),
        ]);
    }

    /**
     * @return mixed
     */
    protected function getClient()
    {
        $redis = Redis::getInstance();

        return $redis->connect();
    }

    /**
     * @deprecated this method will be removed in the nearest major version.
     *
     * @return void
     */
    public function redisAddAction()
    {
        $redis = $this->getClient();

        for ($i = 0; $i < 100; $i++) {
            $redis->set(
                hash(static::COOKIE_HASH_ALGORITHM, microtime(true))
            );
        }
    }

}
