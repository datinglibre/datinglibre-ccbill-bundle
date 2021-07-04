<?php

declare(strict_types=1);

namespace DatingLibre\CcBillBundle\Service;

use DatingLibre\CcBill\Client\CcBillClient;
use DatingLibre\CcBill\Client\CcBillResponseCodeException;
use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CcBillClientService
{
    private const DATA_LINK_API_URL = 'https://datalink.ccbill.com';
    private string $username;
    private string $password;
    private string $clientAccount;
    private string $clientSubAccount;
    private HttpClientInterface $httpClient;

    public function __construct(
        string $username,
        string $password,
        string $clientAccount,
        string $clientSubAccount,
        HttpClientInterface $httpClient
    ) {
        $this->username = $username;
        $this->password = $password;
        $this->clientAccount = $clientAccount;
        $this->clientSubAccount = $clientSubAccount;
        $this->httpClient = $httpClient;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws CcBillResponseCodeException
     * @throws Exception
     */
    public function viewSubscriptionStatus(string $subscriptionId): string
    {
        $response = $this->httpClient->request(
            'GET',
            self::DATA_LINK_API_URL . '/utils/subscriptionManagement.cgi',
            [
                'query' => array_merge($this->getAuthentication(), [
                    'action' => 'viewSubscriptionStatus',
                    'subscriptionId' => $subscriptionId,
                    'returnXML' => '1'
                ])
            ]
        );

        $content = $response->getContent(true);

        $ccBillResponse = CcBillClient::parseResponse($content);

        if ($ccBillResponse->isResponseCode() && !$ccBillResponse->isContent()) {
            return $ccBillResponse->getResponseCode()->getMessage();
        }

        return json_encode($ccBillResponse->getContent());
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws CcBillResponseCodeException
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function cancelSubscription(string $subscriptionId): string
    {
        $response = $this->httpClient->request(
            'GET',
            self::DATA_LINK_API_URL . '/utils/subscriptionManagement.cgi',
            [
                'query' => array_merge($this->getAuthentication(), [
                    'action' => 'cancelSubscription',
                    'subscriptionId' => $subscriptionId,
                    'returnXML' => '1'
                ])
            ]
        );

        return CcBillClient::parseResponse($response->getContent(true))
            ->getResponseCode()
            ->getMessage();
    }

    private function getAuthentication(): array
    {
        return [
            'clientAccnum' => $this->clientAccount,
            'clientSubacc' => $this->clientSubAccount,
            'username' => $this->username,
            'password' => $this->password,
        ];
    }
}
