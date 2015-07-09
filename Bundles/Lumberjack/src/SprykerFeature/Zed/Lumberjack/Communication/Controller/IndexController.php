<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Lumberjack\Communication\Controller;

use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\Lumberjack\LumberjackConfig;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;

class IndexController extends AbstractController
{

    public function indexAction()
    {
        $config = $this->buildConfig();

        return $this->viewResponse([
            'config' => $this->buildConfig(),
        ]);
    }

    /**
     * @throws \Exception
     *
     * @return string
     */
    private function buildConfig()
    {
        $config = Config::get(LumberjackConfig::LUMBERJACK);
        $lumberjackConfig = [
            'container' => '#main', // html element for JACK to live in
            'sticky_top' => 40,
            'default_size' => 25, // number of rows to display
            'uri' => [
                'url' => $config->url, // ES url
                'mapping' => $config->mapping, // mapping uri ( uri.url + uri.mapping )
                'search' => $config->search,   // search uri ( uri.url + uri.search )
            ],
            'sorting' => [// sorting configuration
                'default' => [// default sorting: 'key' => 'asc|desc'
                    'microtime' => 'desc',
                ],
                'histogram' => [// histogram sorting
                    'field' => 'microtime', // field to use when sorting
                    'interval' => 'minute',    // default bucket size
                    'factor' => 10           // factor for field parsing ( if needed )
                ],
                'range' => 'dateAndTime', // date-field used for range searches ( defaults to one day )
            ],
            'keys' => [// keys configuration
                'default' => [// keys that should not be accented in the view
                    'dateAndTime',
                    'environment',
                    'host',
                    'ip',
                    'language',
                    'locale',
                    'message',
                    'requestIdZed',
                    'requestIdYved',
                    'route',
                    'store',
                    'subtype',
                    'type',
                    'url',
                    'application',
                ],
                'hidden' => [// keys hidden in the table ( keep in mind that ES will still count them when searching )
                    'microtime',
                    'dateAndTime',
                ],
                'visibleInOverview' => [// keys that will make it to overview-table - if not provided, will throw up the default structure
                    'dateAndTime',
                    'message',
                    'application',
                    'route',
                    'type',
                    'subtype',
                ],
                'grouped' => [// keys that can be used for 'grouping' ( find logs with the same request_ID? )
                    'requestIdZed' => [
                        'executeOnClick' => true,
                        'linkLabel' => 'Show all logs that happend in this ZED Request',
                        'removeFromTable' => true,
                    ],
                    'requestIdYves' => [
                        'executeOnClick' => true,
                        'linkLabel' => 'Show all logs that happend in this Yves Request',
                        'removeFromTable' => true,
                    ],
                ],
            ],
            'proxy' => $config->proxy, // set to true if you are using ES proxy, false means talking DIRECTLY to ES
        ];

        return json_encode($lumberjackConfig);
    }

}
