<?php

namespace App\PayPal\Services;


use App\PayPal\Models\PaypalToken;
use Neoan\Helper\Env;
use PaypalServerSdkLib\Authentication\ClientCredentialsAuthCredentialsBuilder;
use PaypalServerSdkLib\Authentication\ClientCredentialsAuthManager;
use PaypalServerSdkLib\Environment;
use PaypalServerSdkLib\Logging\LoggingConfigurationBuilder;
use PaypalServerSdkLib\Logging\RequestLoggingConfigurationBuilder;
use PaypalServerSdkLib\Logging\ResponseLoggingConfigurationBuilder;
use PaypalServerSdkLib\Models\OAuthToken;
use PaypalServerSdkLib\PaypalServerSdkClient;
use PaypalServerSdkLib\PaypalServerSdkClientBuilder;
use Psr\Log\LogLevel;

class PayPalClient
{
    private PaypalServerSdkClient $client;
    public function __construct()
    {
        $this->client = PaypalServerSdkClientBuilder::init()
            ->clientCredentialsAuthCredentials(
                ClientCredentialsAuthCredentialsBuilder::init(Env::get('PAYPAL_CLIENTID'), Env::get('PAYPAL_SECRET'))
                    ->oAuthOnTokenUpdate(function (OAuthToken $oAuthToken) {
                        try {
                            $token = PaypalToken::get(1);
                        } catch (\Exception $e) {
                            $token = new PaypalToken();
                        }
                        $token->token = '=' . $oAuthToken->getAccessToken();

                    })
                    ->oAuthTokenProvider(function (?OAuthToken $oAuthToken, ClientCredentialsAuthManager $authManager) {
                        try {
                            $token = PaypalToken::get(1);
                            return $token->token;
                        } catch (\Exception $e) {
                            return $authManager->fetchToken();
                        }
                    })
            )
            ->environment(Env::get('PAYPAL_ENVIRONMENT') === 'SANDBOX' ? Environment::SANDBOX : Environment::PRODUCTION)
            ->loggingConfiguration(
                LoggingConfigurationBuilder::init()
                    ->level(LogLevel::INFO)
                    ->requestConfiguration(RequestLoggingConfigurationBuilder::init()->body(true))
                    ->responseConfiguration(ResponseLoggingConfigurationBuilder::init()->headers(true))
            )
            ->build();
    }
    public function subscriptionController()
    {
        return $this->client->getSubscriptionsController();
    }
}