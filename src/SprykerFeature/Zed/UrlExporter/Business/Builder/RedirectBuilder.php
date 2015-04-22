<?php

namespace SprykerFeature\Zed\UrlExporter\Business\Builder;

use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;

class RedirectBuilder implements RedirectBuilderInterface
{
    /**
     * @var KeyBuilderInterface
     */
    protected $redirectKeyBuilder;

    /**
     * @param KeyBuilderInterface $redirectKeyBuilder
     */
    public function __construct(KeyBuilderInterface $redirectKeyBuilder)
    {
        $this->redirectKeyBuilder = $redirectKeyBuilder;
    }

    /**
     * @param array $redirectResultSet
     * @param string $localeName
     *
     * @return array
     */
    public function buildRedirects(array $redirectResultSet, $localeName)
    {
        $returnedResultSet = [];
        foreach ($redirectResultSet as $index => $redirect) {
            $redirectKey = $this->redirectKeyBuilder->generateKey(['resourceType' => 'redirect', 'value' => $redirect['redirect_id']], $localeName);
            $returnedResultSet[$redirectKey] = [
                'from_url' => $redirect['from_url'],
                'to_url' => $redirect['to_url'],
                'status' => $redirect['status'],
                'id' => $redirect['redirect_id']
            ];
        }

        return $returnedResultSet;
    }
}
