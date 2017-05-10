<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FactFinder\Business\Api;

use Exception;
use FACTFinder\Loader as FactFinderLoader;
use FACTFinder\Util\Parameters;
use Generated\Shared\Transfer\FactFinderSearchRequestTransfer;
use Spryker\Client\FactFinder\FactFinderConfig;

class FactFinderConnector
{

    /**
     * @var \FACTFinder\Util\Pimple
     */
    protected $dic;

    /**
     * @var \FACTFinder\Util\Parameters|null
     */
    protected $requestParameters = null;

    /**
     * @var \Spryker\Client\FactFinder\FactFinderConfig
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

    /**
     * @param \Spryker\Client\FactFinder\FactFinderConfig $factFinderConfig
     */
    public function __construct(FactFinderConfig $factFinderConfig)
    {
        $this->factFinderConfig = $factFinderConfig;
        $this->dic = FactFinderLoader::getInstance('Util\Pimple');

        $this->init();
    }

    /**
     * @return \FACTFinder\Adapter\Search
     */
    public function createSearchAdapter()
    {
        $this->searchAdapter = FactFinderLoader::getInstance(
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
        $this->tagCloudAdapter = FactFinderLoader::getInstance(
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
        $this->recommendationAdapter = FactFinderLoader::getInstance(
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
        $this->suggestAdapter = FactFinderLoader::getInstance(
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
        $this->trackingAdapter = FactFinderLoader::getInstance(
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
        $this->similarRecordsAdapter = FactFinderLoader::getInstance(
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
        $this->productCampaignAdapter = FactFinderLoader::getInstance(
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
        $this->importAdapter = FactFinderLoader::getInstance(
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
        $this->compareAdapter = FactFinderLoader::getInstance(
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
     *
     * @return void
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
        $config = $this->factFinderConfig->getFactFinderConfiguration();
        $parameters = [];
        $parameters['channel'] = $config['channel'];
        $query = trim($searchRequestTransfer->getQuery());
        if (!strlen($query)) {
            $query = '*';
        }
        $parameters['query'] = $query;
        $parameters['page'] = $searchRequestTransfer->getPage();

        return FactFinderLoader::getInstance(
            'Util\Parameters',
            $parameters
        );
    }

    /**
     * @return \FACTFinder\Util\Parameters
     */
    public function createRequestParametersFromRequestParser()
    {
        /** @var \FACTFinder\Util\Parameters $requestParameters */
        $requestParameters = $this->dic['requestParser']->getRequestParameters();
        if (!$requestParameters->offsetExists('query')) {
            $requestParameters->offsetSet('query', '*');
        }
        $query = trim($requestParameters->offsetGet('query'));
        if (!strlen($query)) {
            $requestParameters->offsetSet('query', '*');
        }

        return $requestParameters;
    }

    /**
     * @param \FACTFinder\Util\Parameters $parameters
     *
     * @return \FACTFinder\Data\SearchParameters
     */
    public function createSearchParameters(Parameters $parameters)
    {
        return FactFinderLoader::getInstance(
            'Data\SearchParameters',
            $parameters
        );
    }

    /**
     * @return \FACTFinder\Data\SearchParameters
     */
    public function createSearchParametersFromRequestParser()
    {
        return FactFinderLoader::getInstance(
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
        return FactFinderLoader::getClassName('Data\SearchStatus');
    }

    /**
     * @return \FACTFinder\Data\ArticleNumberSearchStatus
     */
    public function getArticleNumberSearchStatusEnum()
    {
        return FactFinderLoader::getClassName('Data\ArticleNumberSearchStatus');
    }

    /**
     * @throws \Exception
     *
     * @return void
     */
    protected function init()
    {
        $this->dic['loggerClass'] = function ($c) {
            $loggerClass = FactFinderLoader::getClassName('Util\Log4PhpLogger');
            $loggerClass::configure($this->getLog4phpConfigXml());
            return $loggerClass;
        };

        $this->dic['configuration'] = function ($c) {
            return FactFinderLoader::getInstance(
                'Core\ManualConfiguration',
                $this->factFinderConfig->getFactFinderConfiguration()
            );
        };

        $this->dic['request'] = $this->dic->factory(function ($c) {
            return $c['requestFactory']->getRequest();
        });

        $this->dic['requestFactory'] = function ($c) {
            return FactFinderLoader::getInstance(
                'Core\Server\MultiCurlRequestFactory',
                $c['loggerClass'],
                $c['configuration'],
                $this->getRequestParameters()
            );
        };

        $this->dic['clientUrlBuilder'] = function ($c) {
            return FactFinderLoader::getInstance(
                'Core\Client\UrlBuilder',
                $c['loggerClass'],
                $c['configuration'],
                $c['requestParser'],
                $c['encodingConverter']
            );
        };

        $this->dic['requestParser'] = function ($c) {
            return FactFinderLoader::getInstance(
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
                throw new Exception('No encoding conversion available.');

            return FactFinderLoader::getInstance(
                $type,
                $c['loggerClass'],
                $c['configuration']
            );
        };
    }

    /**
     * @return string
     */
    protected function getLog4phpConfigXml()
    {
        return $this->factFinderConfig
            ->getLog4PhpConfigPath();
    }

}
