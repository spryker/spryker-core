<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Lumberjack\Business\Model\Logger;

use Guzzle\Http\QueryString;
use SprykerFeature\Shared\Lumberjack\Code\Log\Types;
use SprykerFeature\Shared\Lumberjack\Code\Lumberjack;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\AbstractLogger;

class RequestLogger extends AbstractLogger
{

    /**
     * @param mixed $level
     * @param string $message
     * @param array $context
     */
    public function log($level, $message, array $context = [])
    {
        if (!($message instanceof Request)) {
            throw new \InvalidArgumentException('$message must be a instance of "SprykerFeature\Zed\Application\Component\Request"');
        }
        $path = $message->attributes->get('module') . '/' . $message->attributes->get('controller') . '/' . $message->attributes->get('action');

        if ($path === 'system/heartbeat/index') {
            return;
        }

        $lumberjack = Lumberjack::getInstance();
        $this->addLumberjackFields($lumberjack, $message);

        $requestMethod = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'cli';

        $lumberjack->addHttpUserAgent();
        $lumberjack->send(Types::REQUEST, $path, $requestMethod);
    }

    /**
     * @param Lumberjack $lumberjack
     * @param Request $request
     */
    protected function addLumberjackFields(Lumberjack $lumberjack, Request $request)
    {
        $params = $request->request->all();
        if (!empty($params)) {
            $lumberjack->addField('params', \SprykerFeature_Zed_Library_Sanitize_Array::fromArray($params, 'SprykerFeature_Zed_Library_Sanitize_FilterSet_Params'));
        }

        if ($request instanceof \Zend_Controller_Request_Http && $request->getRawBody()) {
            $lumberjack->addField('rawBody', $this->decodeRawBody($request->getRawBody()));
        }
    }

    /**
     * @param string $rawBody
     *
     * @return \SprykerFeature_Zed_Library_Sanitize_Array
     */
    protected function decodeRawBody($rawBody)
    {
        // try json
        $body = json_decode($rawBody, true);
        if ($body !== null) {
            return \SprykerFeature_Zed_Library_Sanitize_Array::fromArray(
                $body,
                'SprykerFeature_Zed_Library_Sanitize_FilterSet_RawBody'
            );
        }

        // try xml
        $body = \SprykerFeature_Zed_Library_Xml_Helper::toArray($rawBody);
        if ($body !== null) {
            return \SprykerFeature_Zed_Library_Sanitize_Array::fromArray(
                $body,
                'SprykerFeature_Zed_Library_Sanitize_FilterSet_RawBody'
            );
        }

        // try regular post-query-string
        $body = QueryString::fromString($rawBody);
        if (!empty($body) && count($body)) {
            return \SprykerFeature_Zed_Library_Sanitize_Array::fromArray(
                $body->toArray(),
                'SprykerFeature_Zed_Library_Sanitize_FilterSet_RawBody'
            );
        }

        return $rawBody;
    }

}
