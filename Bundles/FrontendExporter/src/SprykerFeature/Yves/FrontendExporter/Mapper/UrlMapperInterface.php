<?php

namespace SprykerFeature\Yves\FrontendExporter\Mapper;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class UrlMapper
 * @package SprykerFeature\Sdk\Catalog\Model
 * @TODO This class needs a refactoring!!!
 */
interface UrlMapperInterface
{
    /**
     * @param array $mergedParameters
     * @param bool $addTrailingSlash
     * @return string
     */
    public function generateUrlFromParameters(array $mergedParameters, $addTrailingSlash = false);

    /**
     * @param $requestParameters
     * @param $generationParameters
     * @return array
     */
    public function mergeParameters($requestParameters, $generationParameters);

    /**
     * @param $pathinfo
     * @param Request $request
     */
    public function injectParametersFromUrlIntoRequest($pathinfo, Request $request);
}