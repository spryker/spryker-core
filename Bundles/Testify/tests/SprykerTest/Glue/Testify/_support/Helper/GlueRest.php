<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Glue\Testify\Helper;

use Codeception\Exception\ModuleException;
use Codeception\Module\REST;
use Codeception\Util\HttpCode;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use JsonPath\JsonObject;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Testify\TestifyConstants;
use SprykerTest\Glue\Testify\Model\Connection;
use SprykerTest\Glue\Testify\Model\JsonConnection;
use SprykerTest\Shared\CompanyUser\Helper\CompanyUserHelper;
use SprykerTest\Shared\Customer\Helper\CustomerDataHelper;
use SprykerTest\Shared\Testify\Helper\ModuleLocatorTrait;
use SprykerTest\Zed\Company\Helper\CompanyHelper;

class GlueRest extends REST implements LastConnectionProviderInterface
{
    use ModuleLocatorTrait;

    public const DEFAULT_PASSWORD = 'Pass$.123456';

    /**
     * @var \SprykerTest\Glue\Testify\Helper\JsonPath|null
     */
    protected $jsonPathModule;

    /**
     * @var \SprykerTest\Glue\Testify\Model\JsonConnection|null
     */
    protected $lastConnection;

    /**
     * @var \SprykerTest\Zed\Company\Helper\CompanyHelper|null
     */
    protected $companyProvider;

    /**
     * @var \SprykerTest\Shared\CompanyUser\Helper\CompanyUserHelper|null
     */
    protected $companyUserProvider;

    /**
     * @var \SprykerTest\Shared\Customer\Helper\CustomerDataHelper|null
     */
    protected $customerProvider;

    /**
     * @inheritdoc
     */
    public function _initialize(): void
    {
        parent::_initialize();

        $this->companyProvider = $this->findModule(CompanyHelper::class);
        $this->companyUserProvider = $this->findModule(CompanyUserHelper::class);
        $this->customerProvider = $this->findModule(CustomerDataHelper::class);
    }

    /**
     * @inheritdoc
     */
    public function getLastConnection(): ?Connection
    {
        return $this->lastConnection;
    }

    /**
     * @part json
     *
     * @throws \Codeception\Exception\ModuleException
     *
     * @return array
     */
    public function grabResponseJson(): array
    {
        $rawResponse = $this->grabResponse();

        $jsonResponse = json_decode($rawResponse, true); // TODO PHP 7.3 use JSON_THROW_ON_ERROR

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ModuleException(
                'GlueRest',
                sprintf(
                    'Failed to parse json string "%s", error: "%s"',
                    $jsonResponse,
                    json_last_error_msg()
                )
            );
        }

        return $jsonResponse;
    }

    /**
     * Publishes access token
     *
     * @part json
     *
     * @param array $glueToken
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function amAuthorizedGlueUser(array $glueToken, CustomerTransfer $customerTransfer): CustomerTransfer
    {
        $this->haveHttpHeader('Authorization', sprintf('%s %s', $glueToken['tokenType'], $glueToken['accessToken']));

        return $customerTransfer;
    }

    /**
     * Publishes access token
     *
     * @part json
     *
     * @param array $glueToken
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function amAuthorizedGlueCompanyUser(array $glueToken, CustomerTransfer $customerTransfer): CustomerTransfer
    {
        $this->haveHttpHeader('X-Company-User-Id', $customerTransfer->getCompanyUserTransfer()->getUuid());
        $this->haveHttpHeader('Authorization', sprintf('%s %s', $glueToken['tokenType'], $glueToken['accessToken']));

        return $customerTransfer;
    }

    /**
     * @part json
     *
     * @param string $withEmail
     * @param string $withPassword
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function amUser(string $withEmail = '', string $withPassword = ''): CustomerTransfer
    {
        $withEmail = $withEmail ?: sprintf('%s@test.local.com', uniqid('glue-', true));

        return $this->customerProvider
            ? $this->customerProvider->haveCustomer([
                CustomerTransfer::FIRST_NAME => 'John',
                CustomerTransfer::LAST_NAME => 'Doe',
                CustomerTransfer::EMAIL => $withEmail,
                CustomerTransfer::PASSWORD => $withPassword ?: static::DEFAULT_PASSWORD,
                CustomerTransfer::NEW_PASSWORD => $withPassword ?: static::DEFAULT_PASSWORD,
            ])
            : $this->haveCustomerByApi($withEmail, $withPassword);
    }

    /**
     * Creates company, company user and customer
     *
     * @part json
     *
     * @throws \Codeception\Exception\ModuleException
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function amCompanyUser(): CustomerTransfer
    {
        if ($this->companyProvider === null) {
            throw new ModuleException('GlueRest', 'The module requires CompanyHelper');
        }

        $company = uniqid('c-', true);
        $companyTransfer = $this->companyProvider->haveCompany([
            CompanyTransfer::KEY => $company,
        ]);

        return $this->amCompanyUserInCompany($companyTransfer->getIdCompany());
    }

    /**
     * Creates company user and customer
     *
     * @part json
     *
     * @param int $idCompany
     *
     * @throws \Codeception\Exception\ModuleException
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function amCompanyUserInCompany(int $idCompany): CustomerTransfer
    {
        if ($this->companyUserProvider === null) {
            throw new ModuleException('GlueRest', 'The module requires CompanyUserHelper');
        }

        $companyUserUuid = uniqid('cu-', true);

        $customerTransfer = $this->amUser();

        $companyUserTransfer = $this->companyUserProvider->haveCompanyUser([
            CompanyUserTransfer::KEY => $companyUserUuid,
            CompanyUserTransfer::UUID => $companyUserUuid,
            CompanyUserTransfer::FK_COMPANY => $idCompany,
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
        ]);

        $customerTransfer->setCompanyUserTransfer($companyUserTransfer);

        return $this->removeCyclicLinksInCustomerTransfer($customerTransfer);
    }

    /**
     * @part json
     *
     * @param string $withEmail
     * @param string $withPassword
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function haveCustomerByApi(string $withEmail, string $withPassword = ''): CustomerTransfer
    {
        $customerTransfer = (new CustomerTransfer())
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setSalutation('Mr')
            ->setEmail($withEmail)
            ->setNewPassword($withPassword ?: static::DEFAULT_PASSWORD);

        $this->sendPOST('customers', [
            'data' => [
                'type' => 'customers',
                'attributes' => [
                    'salutation' => $customerTransfer->getSalutation(),
                    'firstName' => $customerTransfer->getFirstName(),
                    'lastName' => $customerTransfer->getLastName(),
                    'email' => $customerTransfer->getEmail(),
                    'password' => $customerTransfer->getNewPassword(),
                    'confirmPassword' => $customerTransfer->getNewPassword(),
                    'acceptedTerms' => true,
                ],
            ],
        ]);

        $this->seeResponseCodeIs(HttpCode::CREATED);

        $customerTransfer->setIdCustomer(
            $this->grabDataFromResponseByJsonPath('$.data.id')[0]
        );

        return $customerTransfer;
    }

    /**
     * @part json
     * @part xml
     *
     * @return int
     */
    public function grabResponseCode(): int
    {
        return (int)$this->connectionModule->_getResponseStatusCode();
    }

    /**
     * Extending with response in the message
     *
     * @part json
     *
     * @param int $code
     *
     * @return void
     */
    public function seeResponseCodeIs($code): void
    {
        $rawResponse = $this->grabResponse();
        $responseLimit = 300;
        $failureMessage = sprintf(
            'Expected HTTP Status Code: %s. Actual Status Code: %s with the response "%s"',
            HttpCode::getDescription($code),
            HttpCode::getDescription($this->grabResponseCode()),
            strlen($rawResponse) > $responseLimit ? substr($rawResponse, 0, $responseLimit) . '...' : $rawResponse
        );
        $this->assertEquals($code, $this->grabResponseCode(), $failureMessage);
    }

    /**
     * @part json
     *
     * @param string $jsonPath
     *
     * @return array|mixed
     */
    public function grabDataFromResponseByJsonPath($jsonPath)
    {
        return (new JsonObject($this->connectionModule->_getResponseContent()))->get($jsonPath);
    }

    /**
     * @part json
     *
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return array ["tokenType" => string, "expiresIn" => int, "accessToken" => string, "refreshToken" => string]
     */
    public function haveAuthorizationToGlue(CustomerTransfer $customerTransfer): array
    {
        $this->sendPOST('access-tokens', [
            'data' => [
                'type' => 'access-tokens',
                'attributes' => [
                    'username' => $customerTransfer->getEmail(),
                    'password' => $customerTransfer->getNewPassword() ?: static::DEFAULT_PASSWORD,
                ],
            ],
        ]);

        $this->seeResponseCodeIs(HttpCode::CREATED);

        return $this->grabDataFromResponseByJsonPath('$.data.attributes')[0];
    }

    /**
     * @part json
     *
     * @param string $link
     *
     * @return void
     */
    public function seeResponseLinksContainsSelfLink(string $link): void
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
    public function seeResponseDataContainsSingleResourceOfType(string $type): void
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
    public function seeSingleResourceIdEqualTo(string $id): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains([
            'id' => $id,
        ], '$.data');
    }

    /**
     * @part json
     *
     * @param array $attributes
     *
     * @return void
     */
    public function seeSingleResourceContainsAttributes(array $attributes): void
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
    public function seeSingleResourceHasRelationshipByTypeAndId(string $type, string $id): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains([
            'type' => $type,
            'id' => $id,
        ], sprintf(
            '$.data.relationships.%1$s.data[?(@.id == %2$s and @.type == %3$s)]',
            $type,
            json_encode($id),
            json_encode($type)
        ));
    }

    /**
     * @part json
     *
     * @param string $link
     *
     * @return void
     */
    public function seeSingleResourceHasSelfLink(string $link): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains([
            'self' => $link,
        ], '$.data.links');
    }

    /**
     * @part json
     *
     * @param string $type
     * @param int $size
     *
     * @return void
     */
    public function seeResponseDataContainsResourceCollectionOfTypeWithSizeOf(string $type, int $size): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains([
            'type' => $type,
        ], '$.data[*]');
        $this->assertCount($size, $this->grabDataFromResponseByJsonPath('$.data')[0]);
    }

    /**
     * @part json
     *
     * @param string $id
     *
     * @return void
     */
    public function seeResourceCollectionHasResourceWithId(string $id): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains(
            [
                'id' => $id,
            ],
            sprintf('$.data[?(@.id == %s)]', json_encode($id))
        );
    }

    /**
     * @part json
     *
     * @param string $id
     * @param array $attributes
     *
     * @return void
     */
    public function seeResourceByIdContainsAttributes(string $id, array $attributes): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains(
            $attributes,
            sprintf('$.data[?(@.id == %s)].attributes', json_encode($id))
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
    public function seeResourceByIdHasRelationshipByTypeAndId(string $id, string $relationType, string $relationId): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains([
            'type' => $relationType,
            'id' => $relationId,
        ], sprintf(
            '$.data[?(@.id == %1$s)].relationships.%2$s.data[?(@.id == %3$s and @.type == %4$s)]',
            json_encode($id),
            $relationType,
            json_encode($relationId),
            json_encode($relationType)
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
    public function seeResourceByIdHasSelfLink(string $id, string $link): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains(
            [
                'self' => $link,
            ],
            sprintf('$.data[?(@.id == %s)].links', json_encode($id))
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
    public function seeIncludesContainsResourceByTypeAndId(string $type, string $id): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains(
            [
                'type' => $type,
                'id' => $id,
            ],
            sprintf(
                '$.included[?(@.id == %1$s and @.type == %2$s)]',
                json_encode($id),
                json_encode($type)
            )
        );
    }

    /**
     * @part json
     *
     * @param string $type
     * @param string $id
     * @param array $attributes
     *
     * @return void
     */
    public function seeIncludedResourceByTypeAndIdContainsAttributes(string $type, string $id, array $attributes): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains(
            $attributes,
            sprintf(
                '$.included[?(@.id == %1$s and @.type == %2$s)].attributes',
                json_encode($id),
                json_encode($type)
            )
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
    public function seeIncludedResourceByTypeAndIdHasRelationshipByTypeAndId(
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
            json_encode($relationType)
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
    public function seeIncludedResourceByTypeAndIdHasSelfLink(string $type, string $id, string $link): void
    {
        $this->getJsonPathModule()->seeResponseJsonPathContains(
            [
                'self' => $link,
            ],
            sprintf(
                '$.included[?(@.id == %1$s and @.type == %2$s)].links',
                json_encode($id),
                json_encode($type)
            )
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function removeCyclicLinksInCustomerTransfer(CustomerTransfer $customerTransfer): CustomerTransfer
    {

        if ($customerTransfer->getCompanyUserTransfer()) {
            $customerTransfer->getCompanyUserTransfer()->setCustomer();
        }

        return $customerTransfer;
    }

    /**
     * @inheritdoc
     */
    protected function resetVariables(): void
    {
        $this->lastConnection = null;
    }

    /**
     * @inheritdoc
     */
    protected function execute($method, $url, $parameters = [], $files = [])
    {
        $this->prepareHeaders();

        if (strpos($url, '://') === false) {
            $url = Config::get(TestifyConstants::GLUE_APPLICATION_DOMAIN) . '/' . rtrim($url, '/');
        }

        parent::execute($method, $url, $parameters, $files);

        $this->persistLastConnection($method, $url, $parameters, $files);
    }

    /**
     * @return static
     */
    protected function prepareHeaders(): self
    {
        $this->startFollowingRedirects();
        $this->haveHttpHeader('X-Requested-With', 'Codeception');
        $this->haveHttpHeader('Content-Type', 'application/vnd.api+json');
        $this->haveHttpHeader('Accept', '*/*');

        return $this;
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $parameters
     * @param array $files
     *
     * @return static
     */
    protected function persistLastConnection(string $method, string $url, array $parameters, array $files): self
    {
        $responseBody = $this->grabResponse();
        $jsonResponse = json_decode($responseBody, true);
        $this->lastConnection = (new JsonConnection())
            ->setResponseBody($responseBody)
            ->setResponseJson(is_array($jsonResponse) ? $jsonResponse : null)
            ->setRequestFiles($files)
            ->setRequestMethod($method)
            ->setRequestParameters($parameters)
            ->setRequestUrl(strpos($url, '://') !== false ? $url : $this->config['url'] . $url)
            ->setResponseCode($this->grabResponseCode());

        return $this;
    }

    /**
     * @throws \Codeception\Exception\ModuleException
     *
     * @return \SprykerTest\Glue\Testify\Helper\JsonPath
     */
    protected function getJsonPathModule(): JsonPath
    {
        $this->jsonPathModule = $this->jsonPathModule ?: $this->findModule(JsonPath::class);

        if ($this->jsonPathModule === null) {
            throw new ModuleException('GlueRest', 'The module requires JsonPath');
        }

        return $this->jsonPathModule;
    }
}
