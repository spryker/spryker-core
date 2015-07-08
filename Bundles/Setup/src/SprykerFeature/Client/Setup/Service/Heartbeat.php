<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Setup\Service;

/** @TODO: Remove Symfony Dependency */
use SprykerFeature\Client\Storage\Service\StorageClientInterface;
use Symfony\Component\HttpFoundation\Response;
use Elastica\Index;

class Heartbeat
{

    const HEARTBEAT_OK = 'heartbeat:ok';
    const ERROR_KV_EMPTY = 'Empty KV storage';
    const ERROR_SEARCH_EMPTY = 'Empty Search Index storage';

    /**
     * @var Index
     */
    protected $searchIndex;

    /**
     * @var StorageClientInterface
     */
    protected $kvReader;

    /**
     * @param StorageClientInterface $kvReader
     * @param Index $searchIndex
     */
    public function __construct(StorageClientInterface $kvReader, Index $searchIndex)
    {
        $this->kvReader = $kvReader;
        $this->searchIndex = $searchIndex;
    }

    /**
     * @return Response
     */
    public function getHeartbeatResponse()
    {
        $errors = [];

        try {
            if ($this->kvReader->getCountItems() == 0) {
                $errors[] = self::ERROR_KV_EMPTY;
            }
            if ($this->searchIndex->count() == 0) {
                $errors[] = self::ERROR_SEARCH_EMPTY;
            }
            return $this->getResponse($errors);
        } catch (\Exception $e) {
            return $this->getResponse([$e->getMessage()]);
        }
    }

    /**
     * @param array $errors
     * @return Response
     */
    protected function getResponse(array $errors = [])
    {
        $response = new Response();
        if (count($errors) > 0) {
            $response->setStatusCode(503);
            $content = $this->renderError($errors);
            $response->setContent($content);
        } else {
            $response->setContent(self::HEARTBEAT_OK);
        }

        return $response;
    }

    /**
     * Output format in case of error is fixed and parsed for Nagios
     * "<h1>Critical Errors</h1><ul><li>Search: Is not reachable!</li></ul>
     *
     * @param array $messages
     * @return string
     */
    protected function renderError($messages)
    {
        $errorHtml = '<h1>Heartbeat Critical Errors</h1><ul>';
        foreach ($messages as $message) {
            $errorHtml .= '<li>' . $message . '</li>';
        }
        $errorHtml .= '</ul>';

        return $errorHtml;
    }
}
