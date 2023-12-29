<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\Testify\Helper;

use Codeception\Exception\ModuleException;
use Codeception\Util\HttpCode;
use JsonException;
use JsonPath\JsonObject;
use SprykerTest\Shared\Testify\Helper\ModuleLocatorTrait;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractJsonApiHelper extends RestHelper implements LastConnectionProviderInterface
{
    use ModuleLocatorTrait;

    /**
     * @var \SprykerTest\Glue\Testify\Helper\JsonPath|null
     */
    protected $jsonPathModule;

    /**
     * @var \SprykerTest\Glue\Testify\Helper\JsonConnection|null
     */
    protected $lastConnection;

    /**
     * @return void
     */
    abstract protected function prepareHeaders(): void;

    /**
     * @return string
     */
    abstract protected function getApplicationDomain(): string;

    /**
     * @return \SprykerTest\Glue\Testify\Helper\Connection|null
     */
    public function getLastConnection(): ?Connection
    {
        return $this->lastConnection;
    }

    /**
     * @part json
     *
     * @param string $url
     * @param \JsonSerializable|array<mixed>|string $params
     *
     * @return string|null
     */
    public function sendJsonApiGet(string $url, array $params = []): ?string
    {
        return $this->execute(Request::METHOD_GET, $url, $params);
    }

    /**
     * @part json
     *
     * @param string $url
     * @param \JsonSerializable|array<mixed>|string $params
     * @param array<mixed> $files
     *
     * @return string|null
     */
    public function sendJsonApiPost(string $url, $params = [], array $files = []): ?string
    {
        return $this->execute(Request::METHOD_POST, $url, $params, $files);
    }

    /**
     * @part json
     *
     * @param string $url
     * @param \JsonSerializable|array<mixed>|string $params
     * @param array<mixed> $files
     *
     * @return string|null
     */
    public function sendJsonApiPatch(string $url, $params = [], array $files = []): ?string
    {
        return $this->execute(Request::METHOD_PATCH, $url, $params, $files);
    }

    /**
     * @part json
     *
     * @param string $url
     * @param \JsonSerializable|array<mixed>|string $params
     * @param array<mixed> $files
     *
     * @return string|null
     */
    public function sendJsonApiDelete(string $url, $params = [], array $files = []): ?string
    {
        return $this->execute(Request::METHOD_DELETE, $url, $params, $files);
    }

    /**
     * @part json
     *
     * @throws \Codeception\Exception\ModuleException
     *
     * @return array<mixed>
     */
    public function grabJsonApiResponseJson(): array
    {
        $rawResponse = $this->grabResponse();

        try {
            $jsonResponse = json_decode($rawResponse, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new ModuleException(
                'GlueRest',
                sprintf('Failed to parse json string "%s", error: "%s"', $rawResponse, $e->getMessage()),
            );
        }

        return $jsonResponse;
    }

    /**
     * @part json
     *
     * @return int
     */
    public function grabJsonApiResponseCode(): int
    {
        return (int)$this->connectionModule->_getResponseStatusCode();
    }

    /**
     * @part json
     *
     * @param int $code
     *
     * @return void
     */
    public function seeJsonApiResponseCodeIs(int $code): void
    {
        $rawResponse = $this->grabResponse();
        $responseLimit = 300;
        $failureMessage = sprintf(
            'Expected HTTP Status Code: %s. Actual Status Code: %s with the response "%s"',
            HttpCode::getDescription($code),
            HttpCode::getDescription($this->grabJsonApiResponseCode()),
            strlen($rawResponse) > $responseLimit ? substr($rawResponse, 0, $responseLimit) . '...' : $rawResponse,
        );
        $this->assertSame($code, $this->grabJsonApiResponseCode(), $failureMessage);
    }

    /**
     * @part json
     *
     * @param string $jsonPath
     *
     * @return mixed|array|false
     */
    public function getJsonApiDataFromResponseByJsonPath(string $jsonPath)
    {
        return (new JsonObject($this->connectionModule->_getResponseContent(), true))->get($jsonPath);
    }

    /**
     * @part json
     *
     * @param string $link
     *
     * @return void
     */
    public function seeJsonApiResponseLinksContainsSelfLink(string $link): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains([
            'self' => $link,
        ], '$.links');
    }

    /**
     * @part json
     *
     * @param string $type
     *
     * @return void
     */
    public function seeJsonApiResponseDataContainsSingleResourceOfType(string $type): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains([
            'type' => $type,
        ], '$.data');
    }

    /**
     * @part json
     *
     * @param string $id
     *
     * @return void
     */
    public function seeJsonApiSingleResourceIdEqualTo(string $id): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains([
            'id' => $id,
        ], '$.data');
    }

    /**
     * @part json
     *
     * @param array<mixed> $attributes
     *
     * @return void
     */
    public function seeJsonApiSingleResourceContainsAttributes(array $attributes): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains($attributes, '$.data.attributes');
    }

    /**
     * @part json
     *
     * @param string $type
     * @param string $id
     *
     * @return void
     */
    public function seeJsonApiSingleResourceHasRelationshipByTypeAndId(string $type, string $id): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains([
            'type' => $type,
            'id' => $id,
        ], sprintf(
            '$.data.relationships.%1$s.data[?(@.id == %2$s and @.type == %3$s)]',
            $type,
            json_encode($id),
            json_encode($type),
        ));
    }

    /**
     * @part json
     *
     * @param string $link
     *
     * @return void
     */
    public function seeJsonApiSingleResourceHasSelfLink(string $link): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains([
            'self' => $link,
        ], '$.data.links');
    }

    /**
     * @part json
     *
     * @param string $type
     *
     * @return void
     */
    public function seeJsonApiResponseDataContainsResourceCollectionOfType(string $type): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains([
            'type' => $type,
        ], '$.data[*]');
    }

    /**
     * @part json
     *
     * @param string $type
     * @param int $size
     *
     * @return void
     */
    public function seeJsonApiResponseDataContainsResourceCollectionOfTypeWithSizeOf(string $type, int $size): void
    {
        $this->seeJsonApiResponseDataContainsResourceCollectionOfType($type);
        $this->assertCount($size, $this->getJsonApiDataFromResponseByJsonPath('$.data'));
    }

    /**
     * @part json
     *
     * @return void
     */
    public function seeJsonApiResponseDataContainsEmptyCollection(): void
    {
        $this->getJsonPathModule()->dontSeeResponseMatchesJsonPath('$.data[*]');
    }

    /**
     * @part json
     *
     * @return void
     */
    public function seeJsonApiResponseDataContainsNonEmptyCollection(): void
    {
        $this->getJsonPathModule()->seeResponseMatchesJsonPath('$.data[*]');
    }

    /**
     * @part json
     *
     * @param string $resourceName
     * @param string $identifier
     *
     * @return mixed|array|false
     */
    public function grabJsonApiIncludedByTypeAndId(string $resourceName, string $identifier)
    {
        $jsonPath = sprintf(
            '$..included[?(@.type == \'%s\' and @.id == \'%s\')].attributes',
            $resourceName,
            $identifier,
        );

        return $this->getJsonApiDataFromResponseByJsonPath($jsonPath)[0];
    }

    /**
     * @part json
     *
     * @param string $id
     *
     * @return void
     */
    public function seeJsonApiResourceCollectionHasResourceWithId(string $id): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains(
            [
                'id' => $id,
            ],
            sprintf('$.data[?(@.id == %s)]', json_encode($id)),
        );
    }

    /**
     * @part json
     *
     * @param string $id
     * @param array<mixed> $attributes
     *
     * @return void
     */
    public function seeJsonApiResourceByIdContainsAttributes(string $id, array $attributes): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains(
            $attributes,
            sprintf('$.data[?(@.id == %s)].attributes', json_encode($id)),
        );
    }

    /**
     * @part json
     *
     * @param string $id
     * @param string $relationType
     * @param string $relationId
     *
     * @return void
     */
    public function seeJsonApiResourceByIdHasRelationshipByTypeAndId(string $id, string $relationType, string $relationId): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains([
            'type' => $relationType,
            'id' => $relationId,
        ], sprintf(
            '$.data[?(@.id == %1$s)].relationships.%2$s.data[?(@.id == %3$s and @.type == %4$s)]',
            json_encode($id),
            $relationType,
            json_encode($relationId),
            json_encode($relationType),
        ));
    }

    /**
     * @part json
     *
     * @param string $id
     * @param string $link
     *
     * @return void
     */
    public function seeJsonApiResourceByIdHasSelfLink(string $id, string $link): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains(
            [
                'self' => $link,
            ],
            sprintf('$.data[?(@.id == %s)].links', json_encode($id)),
        );
    }

    /**
     * @part json
     *
     * @param string $type
     * @param string $id
     *
     * @return void
     */
    public function seeJsonApiIncludesContainsResourceByTypeAndId(string $type, string $id): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains(
            [
                'type' => $type,
                'id' => $id,
            ],
            sprintf(
                '$.included[?(@.id == %1$s and @.type == %2$s)]',
                json_encode($id),
                json_encode($type),
            ),
        );
    }

    /**
     * @part json
     *
     * @param string $type
     *
     * @return void
     */
    public function dontSeeJsonApiIncludesContainResourceOfType(string $type): void
    {
        $this->getJsonPathModule()->dontSeeResponseMatchesJsonPath(
            sprintf('$.included[?(@.type == %1$s)]', json_encode($type)),
        );
    }

    /**
     * @part json
     *
     * @param string $type
     * @param string $id
     * @param array<mixed> $attributes
     *
     * @return void
     */
    public function seeJsonApiIncludedResourceByTypeAndIdContainsAttributes(string $type, string $id, array $attributes): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains(
            $attributes,
            sprintf(
                '$.included[?(@.id == %1$s and @.type == %2$s)].attributes',
                json_encode($id),
                json_encode($type),
            ),
        );
    }

    /**
     * @part json
     *
     * @param string $type
     * @param string $id
     * @param string $relationType
     * @param string $relationId
     *
     * @return void
     */
    public function seeJsonApiIncludedResourceByTypeAndIdHasRelationshipByTypeAndId(
        string $type,
        string $id,
        string $relationType,
        string $relationId
    ): void {
        $this->getJsonPathModule()->seeResponseJsonPathContains([
            'type' => $relationType,
            'id' => $relationId,
        ], sprintf(
            '$.included[?(@.id == %1$s and @.type == %2$s)].relationships.%3$s.data[?(@.id == %4$s and @.type == %5$s)]',
            json_encode($id),
            json_encode($type),
            $relationType,
            json_encode($relationId),
            json_encode($relationType),
        ));
    }

    /**
     * @part json
     *
     * @param string $type
     * @param string $id
     * @param string $link
     *
     * @return void
     */
    public function seeJsonApiIncludedResourceByTypeAndIdHasSelfLink(string $type, string $id, string $link): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains(
            [
                'self' => $link,
            ],
            sprintf(
                '$.included[?(@.id == %1$s and @.type == %2$s)].links',
                json_encode($id),
                json_encode($type),
            ),
        );
    }

    /**
     * @part json
     *
     * @param string $attribute
     *
     * @return void
     */
    public function seeJsonApiSingleResourceHasAttribute(string $attribute): void
    {
        $this->getJsonPathModule()->seeResponseMatchesJsonPath(
            sprintf(
                '$.data.attributes.%s',
                $attribute,
            ),
        );
    }

    /**
     * @part json
     *
     * @param string $attribute
     *
     * @return void
     */
    public function seeJsonApiResourceCollectionHasAttribute(string $attribute): void
    {
        $this->getJsonPathModule()->seeResponseMatchesJsonPath(
            sprintf(
                '$.data[*].attributes.%s',
                $attribute,
            ),
        );
    }

    /**
     * @part json
     *
     * @param string $code
     * @param string $index
     *
     * @return void
     */
    public function seeJsonApiResponseErrorsHaveCode(string $code, string $index = '*'): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains([
            'code' => $code,
        ], sprintf('$.errors[%s]', $index));
    }

    /**
     * @part json
     *
     * @param int $status
     * @param string $index
     *
     * @return void
     */
    public function seeJsonApiResponseErrorsHaveStatus(int $status, string $index = '*'): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains([
            'status' => $status,
        ], sprintf('$.errors[%s]', $index));
    }

    /**
     * @part json
     *
     * @param string $detail
     * @param string $index
     *
     * @return void
     */
    public function seeJsonApiResponseErrorsHaveDetail(string $detail, string $index = '*'): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains([
            'detail' => $detail,
        ], sprintf('$.errors[%s]', $index));
    }

    /**
     * @part json
     *
     * @param string $type
     *
     * @return void
     */
    public function seeJsonApiIncludesContainResourceOfType(string $type): void
    {
        $this->getJsonPathModule()->seeResponseMatchesJsonPath(
            sprintf('$.included[?(@.type == %1$s)]', json_encode($type)),
        );
    }

    /**
     * @inheritDoc
     */
    protected function resetVariables(): void
    {
        $this->lastConnection = null;
    }

    /**
     * @param string $method
     * @param string $url
     * @param \JsonSerializable|array<mixed>|string $parameters
     * @param array<mixed> $files
     *
     * @return string|null
     */
    protected function execute($method, $url, $parameters = [], $files = []): ?string
    {
        $this->prepareHeaders();

        if (!str_contains($url, '://')) {
            $url = $this->getApplicationDomain() . '/' . rtrim($url, '/');
        }

        $response = parent::execute($method, $url, $parameters, $files);

        $this->persistLastConnection($method, $url, $parameters, $files);

        return $response;
    }

    /**
     * @param string $method
     * @param string $url
     * @param object|array<string, mixed>|string $parameters
     * @param array<mixed> $files
     *
     * @return static
     */
    protected function persistLastConnection(string $method, string $url, $parameters, array $files): self
    {
        $responseBody = $this->grabResponse();
        $jsonResponse = json_decode($responseBody, true);
        $this->lastConnection = (new JsonConnection())
            ->setResponseBody($responseBody)
            ->setResponseJson(is_array($jsonResponse) ? $jsonResponse : null)
            ->setRequestFiles($files)
            ->setRequestMethod($method)
            ->setRequestParameters($parameters)
            ->setRequestUrl(str_contains($url, '://') ? $url : $this->config['url'] . $url)
            ->setResponseCode($this->grabJsonApiResponseCode());

        return $this;
    }

    /**
     * @return \SprykerTest\Glue\Testify\Helper\JsonPath
     */
    protected function getJsonPathModule(): JsonPath
    {
        if (!$this->jsonPathModule instanceof JsonPath) {
            /** @var \SprykerTest\Glue\Testify\Helper\JsonPath $module */
            $module = $this->locateModule(JsonPath::class);
            $this->jsonPathModule = $module;
        }

        return $this->jsonPathModule;
    }
}
