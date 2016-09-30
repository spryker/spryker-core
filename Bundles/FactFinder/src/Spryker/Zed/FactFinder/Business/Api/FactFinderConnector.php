<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business\Api;

use FACTFinder\Loader as FF;
use FACTFinder\Util\Parameters;
use Generated\Shared\Transfer\FactFinderSearchRequestTransfer;
use Spryker\Zed\FactFinder\FactFinderConfig;

class FactFinderConnector
{

    /**
     * @var \FACTFinder\Util\Pimple
     */
    protected $dic;

    /**
     * @var \FACTFinder\Util\Parameters
     */
    protected $requestParameters = null;

    /**
     * @var \Spryker\Zed\FactFinder\FactFinderConfig
     */
    protected $factFinderConfig;

    /**
     * @var \FACTFinder\Adapter\Search
     */
    protected $searchAdapter;

    /**
     * @var \FACTFinder\Adapter\TagCloud
     */
    protected $tagCloudAdapter;

    /**
     * @var \FACTFinder\Adapter\Recommendation
     */
    protected $recommendationAdapter;

    /**
     * @var \FACTFinder\Adapter\Suggest
     */
    protected $suggestAdapter;

    /**
     * @var \FACTFinder\Adapter\Tracking
     */
    protected $trackingAdapter;

    /**
     * @var \FACTFinder\Adapter\SimilarRecords
     */
    protected $similarRecordsAdapter;

    /**
     * @var \FACTFinder\Adapter\ProductCampaign
     */
    protected $productCampaignAdapter;

    /**
     * @var \FACTFinder\Adapter\Import
     */
    protected $importAdapter;

    /**
     * @var \FACTFinder\Adapter\Compare
     */
    protected $compareAdapter;

//    /**
//     * @var \FACTFinder\Util\Log4PhpLogger
//     */
//    protected $logger;
//
//    /**
//     * @var \FACTFinder\Core\XmlConfiguration
//     */
//    protected $configuration;

    /**
     * @param \Spryker\Zed\FactFinder\FactFinderConfig $factFinderConfig
     */
    public function __construct(FactFinderConfig $factFinderConfig)
    {
        $this->factFinderConfig = $factFinderConfig;
        $this->dic = FF::getInstance('Util\Pimple');

        $this->init();
    }

    /**
     * @return \FACTFinder\Adapter\Search
     */
    public function createSearchAdapter()
    {
        $this->searchAdapter = FF::getInstance(
            'Adapter\Search',
            $this->dic['loggerClass'],
            $this->dic['configuration'],
            $this->dic['request'],
            $this->dic['clientUrlBuilder']
        );

        return $this->searchAdapter;
    }

    /**
     * @return \FACTFinder\Adapter\TagCloud
     */
    public function createTagCloudAdapter()
    {
        $this->tagCloudAdapter = FF::getInstance(
            'Adapter\TagCloud',
            $this->dic['loggerClass'],
            $this->dic['configuration'],
            $this->dic['request'],
            $this->dic['clientUrlBuilder']
        );

        return $this->tagCloudAdapter;
    }

    /**
     * @return \FACTFinder\Adapter\Recommendation
     */
    public function createRecommendationAdapter()
    {
        $this->recommendationAdapter = FF::getInstance(
            'Adapter\Recommendation',
            $this->dic['loggerClass'],
            $this->dic['configuration'],
            $this->dic['request'],
            $this->dic['clientUrlBuilder']
        );

        return $this->recommendationAdapter;
    }

    /**
     * @return \FACTFinder\Adapter\Suggest
     */
    public function createSuggestAdapter()
    {
        $this->suggestAdapter = FF::getInstance(
            'Adapter\Suggest',
            $this->dic['loggerClass'],
            $this->dic['configuration'],
            $this->dic['request'],
            $this->dic['clientUrlBuilder']
        );

        return $this->suggestAdapter;
    }

    /**
     * @return \FACTFinder\Adapter\Tracking
     */
    public function createTrackingAdapter()
    {
        $this->trackingAdapter = FF::getInstance(
            'Adapter\Tracking',
            $this->dic['loggerClass'],
            $this->dic['configuration'],
            $this->dic['request'],
            $this->dic['clientUrlBuilder']
        );

        return $this->trackingAdapter;
    }

    /**
     * @return \FACTFinder\Adapter\SimilarRecords
     */
    public function createSimilarRecordsAdapter()
    {
        $this->similarRecordsAdapter = FF::getInstance(
            'Adapter\SimilarRecords',
            $this->dic['loggerClass'],
            $this->dic['configuration'],
            $this->dic['request'],
            $this->dic['clientUrlBuilder']
        );

        return $this->similarRecordsAdapter;
    }

    /**
     * @return \FACTFinder\Adapter\ProductCampaign
     */
    public function createProductCampaignAdapter()
    {
        $this->productCampaignAdapter = FF::getInstance(
            'Adapter\ProductCampaign',
            $this->dic['loggerClass'],
            $this->dic['configuration'],
            $this->dic['request'],
            $this->dic['clientUrlBuilder']
        );

        return $this->productCampaignAdapter;
    }

    /**
     * @return \FACTFinder\Adapter\Import
     */
    public function createImportAdapter()
    {
        $this->importAdapter = FF::getInstance(
            'Adapter\Import',
            $this->dic['loggerClass'],
            $this->dic['configuration'],
            $this->dic['request'],
            $this->dic['clientUrlBuilder']
        );

        return $this->importAdapter;
    }

    /**
     * @return \FACTFinder\Adapter\Compare
     */
    public function createCompareAdapter()
    {
        $this->compareAdapter = FF::getInstance(
            'Adapter\Compare',
            $this->dic['loggerClass'],
            $this->dic['configuration'],
            $this->dic['request'],
            $this->dic['clientUrlBuilder']
        );

        return $this->compareAdapter;
    }

    /**
     * @return string
     */
    public function getPageContentEncoding()
    {
        return $this->dic['configuration']->getPageContentEncoding();
    }

    /**
     * @return \FACTFinder\Util\Parameters
     */
    public function getRequestParameters()
    {
        return $this->requestParameters;
    }

    /**
     * @param \FACTFinder\Util\Parameters $requestParameters
     */
    public function setRequestParameters($requestParameters)
    {
        $this->requestParameters = $requestParameters;
    }

    /**
     * @param \Generated\Shared\Transfer\FactFinderSearchRequestTransfer $searchRequestTransfer
     *
     * @return \FACTFinder\Util\Parameters
     */
    public function createRequestParametersFromSearchRequestTransfer(FactFinderSearchRequestTransfer $searchRequestTransfer)
    {
        $config = $this->factFinderConfig->getFFConfiguration();
        $parameters = [];
        $parameters['channel'] = $config['channel'];
        $parameters['query'] = $searchRequestTransfer->getQuery();
        $parameters['page'] = $searchRequestTransfer->getPage();

        return FF::getInstance(
            'Util\Parameters',
            $parameters
        );
    }

    /**
     * @return \FACTFinder\Util\Parameters
     */
    public function createRequestParametersFromRequestParser()
    {
        return $this->dic['requestParser']->getRequestParameters();
    }

    /**
     * @param \FACTFinder\Util\Parameters $parameters
     *
     * @return \FACTFinder\Data\SearchParameters
     */
    public function createSearchParameters(Parameters $parameters)
    {
        return FF::getInstance(
            'Data\SearchParameters',
            $parameters
        );
    }

    /**
     * @return \FACTFinder\Data\SearchParameters
     */
    public function createSearchParametersFromRequestParser()
    {
        return FF::getInstance(
            'Data\SearchParameters',
            $this->dic['requestParser']->getRequestParameters()
        );
    }

    /**
     * @return string
     */
    public function getRequestTarget()
    {
        return $this->dic['requestParser']->getRequestTarget();
    }

    /**
     * @return string
     */
    public function getSessionId()
    {
        $sid = session_id();
        if ($sid === '') {
            session_start();
            $sid = session_id();
        }

        return $sid;
    }

    /**
     * @return \FACTFinder\Data\SearchStatus
     */
    public function getSearchStatusEnum()
    {
        return FF::getClassName('Data\SearchStatus');
    }

    /**
     * @return \FACTFinder\Data\ArticleNumberSearchStatus
     */
    public function getArticleNumberSearchStatusEnum()
    {
        return FF::getClassName('Data\ArticleNumberSearchStatus');
    }



//    /**
//     * @return \FACTFinder\Util\Log4PhpLogger
//     * @throws \Exception
//     */
//    public function createLogger()
//    {
//        $logger = FF::getClassName('Util\Log4PhpLogger');
//        $logger::configure($this->getLog4phpConfigXml());
//        $this->logger = $logger;
//
//        return $this->logger;
//    }
//
//    /**
//     * @return \FACTFinder\Core\XmlConfiguration
//     * @throws \Exception
//     */
//    public function createConfiguration()
//    {
//        $this->configuration = FF::getInstance(
//            'Core\XmlConfiguration',
//            $this->getConfigXml(),
//            $this->factFinderConfig->getEnv()
//        );
//
//        return $this->configuration;
//    }


    protected function init()
    {
        $this->dic['loggerClass'] = function ($c) {
            $loggerClass = FF::getClassName('Util\Log4PhpLogger');
            $loggerClass::configure($this->getLog4phpConfigXml());
            return $loggerClass;
        };

        $this->dic['configuration'] = function ($c) {
            return FF::getInstance(
                'Core\ManualConfiguration',
                $this->factFinderConfig->getFFConfiguration()
            );
        };

        $this->dic['request'] = $this->dic->factory(function ($c) {
            return $c['requestFactory']->getRequest();
        });

        $this->dic['requestFactory'] = function ($c) {
            return FF::getInstance(
                'Core\Server\MultiCurlRequestFactory',
                $c['loggerClass'],
                $c['configuration'],
                $this->getRequestParameters()
            );
        };

        $this->dic['clientUrlBuilder'] = function ($c) {
            return FF::getInstance(
                'Core\Client\UrlBuilder',
                $c['loggerClass'],
                $c['configuration'],
                $c['requestParser'],
                $c['encodingConverter']
            );
        };

        $this->dic['requestParser'] = function ($c) {
            return FF::getInstance(
                'Core\Client\RequestParser',
                $c['loggerClass'],
                $c['configuration'],
                $c['encodingConverter']
            );
        };

        $this->dic['encodingConverter'] = function ($c) {
            if (extension_loaded('iconv'))
                $type = 'Core\IConvEncodingConverter';
            elseif (function_exists('utf8_encode')
                && function_exists('utf8_decode'))
                $type = 'Core\Utf8EncodingConverter';
            else
                throw new \Exception('No encoding conversion available.');

            return FF::getInstance(
                $type,
                $c['loggerClass'],
                $c['configuration']
            );
        };
    }

    /**
     * @return string
     */
    protected function getConfigXml()
    {
        $filePath = APPLICATION_ROOT_DIR . '/src/Pyz/Zed/FactFinder/config/config.xml';
        if (!is_file($filePath)) {
            $filePath = __DIR__ . '/../../../../../../config/config.xml';
        }
        return $filePath;
    }

    /**
     * @return string
     */
    protected function getLog4phpConfigXml()
    {
        $filePath = APPLICATION_ROOT_DIR . '/src/Pyz/Zed/FactFinder/config/log4php.xml';
        if (!is_file($filePath)) {
            $filePath = __DIR__ . '/../../../../../../config/log4php.xml';
        }
        return $filePath;
    }

}
