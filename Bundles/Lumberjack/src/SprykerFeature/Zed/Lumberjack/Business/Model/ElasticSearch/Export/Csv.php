<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Lumberjack\Business\Model\ElasticSearch\Export;

class Csv
{

    const MAX_COLUMN_LENGTH = 2000; // in bytes

    /**
     * @param string $json
     * @param string $fieldDelimiter
     * @param string $stringDelimiter
     *
     * @return string
     */
    public function getCsvFromElasticSearchJsonResponse($json, $fieldDelimiter = ';', $stringDelimiter = '"')
    {
        $responseObject = json_decode($json, true);
        $data = $responseObject['hits']['hits'];

        $logData = [];
        foreach ($data as $entry) {
            $logData[] = $entry['_source'];
        }

        $keyArray = [];
        foreach ($logData as $entry) {
            foreach (array_keys($entry) as $keyName) {
                $keyArray[$keyName] = '';
            }
        }

        $exportData = [];
        foreach ($logData as $entry) {
            $line = [];
            foreach ($keyArray as $key => $value) {
                if (array_key_exists($key, $entry)) {
                    $data = substr($entry[$key], 0, self::MAX_COLUMN_LENGTH);
                    $data = str_replace('"', "'", $data);
                    $data = str_replace(["\n", "\r", "\t", PHP_EOL], '', $data);
                    $line[$key] = str_replace('"', "'", $data);
                } else {
                    $line[$key] = '';
                }
            }
            $exportData[] = $line;
        }

        $headline = $this->getDelimitedLineFromArray(array_keys($keyArray), $fieldDelimiter, $stringDelimiter);

        $content = '';
        foreach ($exportData as $value) {
            $content .= $this->getDelimitedLineFromArray($value, $fieldDelimiter, $stringDelimiter) . PHP_EOL;
        }

        return $headline . PHP_EOL . $content;
    }

    /**
     * @param array $data
     * @param string $fieldDelimiter
     * @param string $stringDelimiter
     *
     * @return string
     */
    protected function getDelimitedLineFromArray(array $data, $fieldDelimiter, $stringDelimiter)
    {
        $content = '';
        foreach ($data as $value) {
            $content .= $stringDelimiter . $value . $stringDelimiter . $fieldDelimiter;
        }

        return mb_substr($content, 0, -1);
    }

}
