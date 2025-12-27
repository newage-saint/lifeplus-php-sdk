<?php

namespace LifePlus\SDK;

use LifePlus\SDK\Api\AuthApi;
use LifePlus\SDK\Api\ProductsApi;
use LifePlus\SDK\Api\DoctorsApi;
use LifePlus\SDK\Api\HospitalsApi;
use LifePlus\SDK\Api\AppointmentsApi;
use LifePlus\SDK\Api\OrdersApi;
use LifePlus\SDK\Api\CartApi;
use LifePlus\SDK\Api\PackagesApi;
use LifePlus\SDK\Api\AddressesApi;
use LifePlus\SDK\Api\AmbulanceApi;
use LifePlus\SDK\Api\HomeSampleApi;
use LifePlus\SDK\Api\HomeCareApi;
use LifePlus\SDK\Api\TelemedicineApi;
use LifePlus\SDK\Api\WellbeingApi;
use LifePlus\SDK\Api\PartnersApi;
use LifePlus\SDK\Api\LookupApi;
use LifePlus\SDK\Configuration;
use LifePlus\SDK\Model\SessionRequest;
use LifePlus\SDK\Model\SessionResponse;
use GuzzleHttp\Client;

/**
 * LifePlusClient - High-level wrapper for LifePlus Healthcare Platform API
 * 
 * This class provides a simplified interface to the LifePlus API with automatic
 * session management and convenient access to all API endpoints.
 * 
 * @package LifePlus\SDK
 * @version 3.1.0
 */
class LifePlusClient
{
    private Configuration $config;
    private ?string $accessToken = null;
    private ?SessionResponse $session = null;
    
    // API instances
    private ?AuthApi $authApi = null;
    private ?ProductsApi $productsApi = null;
    private ?DoctorsApi $doctorsApi = null;
    private ?HospitalsApi $hospitalsApi = null;
    private ?AppointmentsApi $appointmentsApi = null;
    private ?OrdersApi $ordersApi = null;
    private ?CartApi $cartApi = null;
    private ?PackagesApi $packagesApi = null;
    private ?AddressesApi $addressesApi = null;
    private ?AmbulanceApi $ambulanceApi = null;
    private ?HomeSampleApi $homeSampleApi = null;
    private ?HomeCareApi $homeCareApi = null;
    private ?TelemedicineApi $telemedicineApi = null;
    private ?WellbeingApi $wellbeingApi = null;
    private ?PartnersApi $partnersApi = null;
    private ?LookupApi $lookupApi = null;

    /**
     * Create a new LifePlusClient instance
     * 
     * @param string $baseUrl Base URL of the API (e.g., "https://api.lifeplusbd.com/api/v2")
     * @param array $options Additional configuration options
     */
    public function __construct(string $baseUrl, array $options = [])
    {
        $this->config = Configuration::getDefaultConfiguration();
        $this->config->setHost($baseUrl);
        
        // Apply additional options
        if (isset($options['debug'])) {
            $this->config->setDebug($options['debug']);
        }
        if (isset($options['timeout'])) {
            // Timeout can be set via HTTP client options
        }
    }

    /**
     * Login with phone and password
     * 
     * @param string $phone Phone number (e.g., "01712345678")
     * @param string $password User password
     * @return SessionResponse Session information with user data and token
     * @throws \Exception on authentication failure
     */
    public function login(string $phone, string $password): SessionResponse
    {
        $authApi = $this->auth();
        $request = new SessionRequest([
            'phone' => $phone,
            'password' => $password
        ]);
        
        $this->session = $authApi->createSession($request);

        $token = $this->session->getData()->getToken();
        if ($token !== null) {
            $this->accessToken = $token;
            $this->config->setAccessToken($this->accessToken);
        }

        return $this->session;
    }

    /**
     * Verify phone number with OTP
     * 
     * @param string $phone Phone number
     * @param string $otp OTP code received via SMS
     * @return SessionResponse Session information with user data and token
     * @throws \Exception on verification failure
     */
    public function verifyPhone(string $phone, string $otp): SessionResponse
    {
        $authApi = $this->auth();
        $this->session = $authApi->verifyPhone($phone, $otp);

        $token = $this->session->getData()->getToken();
        if ($token !== null) {
            $this->accessToken = $token;
            $this->config->setAccessToken($this->accessToken);
        }

        return $this->session;
    }

    /**
     * Logout the current user
     * 
     * @return void
     * @throws \Exception on logout failure
     */
    public function logout(): void
    {
        if ($this->accessToken) {
            $authApi = $this->auth();
            $authApi->logout();
            $this->accessToken = null;
            $this->session = null;
            $this->config->setAccessToken(null);
        }
    }

    /**
     * Set authentication token manually
     * 
     * @param string $token Access token
     * @return void
     */
    public function setAccessToken(string $token): void
    {
        $this->accessToken = $token;
        $this->config->setAccessToken($token);
    }

    /**
     * Set Partner API credentials (server-to-server).
     *
     * Uses the API v2 partner headers:
     * - X-API-Key
     * - X-Partner-ID
     */
    public function setPartnerCredentials(string $partnerId, string $apiKey): void
    {
        $this->config->setApiKey('apiKeyAuth', $apiKey);
        $this->config->setApiKey('partnerIdAuth', $partnerId);
    }

    /**
     * Get current access token
     * 
     * @return string|null Current access token or null if not authenticated
     */
    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    /**
     * Get current session information
     * 
     * @return SessionResponse|null Current session or null if not authenticated
     */
    public function getSession(): ?SessionResponse
    {
        return $this->session;
    }

    /**
     * Check if user is authenticated
     * 
     * @return bool True if authenticated, false otherwise
     */
    public function isAuthenticated(): bool
    {
        return $this->accessToken !== null;
    }

    // API Accessors

    public function auth(): AuthApi
    {
        if ($this->authApi === null) {
            $this->authApi = new AuthApi(new Client(), $this->config);
        }
        return $this->authApi;
    }

    public function products(): ProductsApi
    {
        if ($this->productsApi === null) {
            $this->productsApi = new ProductsApi(new Client(), $this->config);
        }
        return $this->productsApi;
    }

    public function doctors(): DoctorsApi
    {
        if ($this->doctorsApi === null) {
            $this->doctorsApi = new DoctorsApi(new Client(), $this->config);
        }
        return $this->doctorsApi;
    }

    public function hospitals(): HospitalsApi
    {
        if ($this->hospitalsApi === null) {
            $this->hospitalsApi = new HospitalsApi(new Client(), $this->config);
        }
        return $this->hospitalsApi;
    }

    public function appointments(): AppointmentsApi
    {
        if ($this->appointmentsApi === null) {
            $this->appointmentsApi = new AppointmentsApi(new Client(), $this->config);
        }
        return $this->appointmentsApi;
    }

    public function orders(): OrdersApi
    {
        if ($this->ordersApi === null) {
            $this->ordersApi = new OrdersApi(new Client(), $this->config);
        }
        return $this->ordersApi;
    }

    public function cart(): CartApi
    {
        if ($this->cartApi === null) {
            $this->cartApi = new CartApi(new Client(), $this->config);
        }
        return $this->cartApi;
    }

    public function packages(): PackagesApi
    {
        if ($this->packagesApi === null) {
            $this->packagesApi = new PackagesApi(new Client(), $this->config);
        }
        return $this->packagesApi;
    }

    public function addresses(): AddressesApi
    {
        if ($this->addressesApi === null) {
            $this->addressesApi = new AddressesApi(new Client(), $this->config);
        }
        return $this->addressesApi;
    }

    public function ambulance(): AmbulanceApi
    {
        if ($this->ambulanceApi === null) {
            $this->ambulanceApi = new AmbulanceApi(new Client(), $this->config);
        }
        return $this->ambulanceApi;
    }

    public function homeSample(): HomeSampleApi
    {
        if ($this->homeSampleApi === null) {
            $this->homeSampleApi = new HomeSampleApi(new Client(), $this->config);
        }
        return $this->homeSampleApi;
    }

    public function homeCare(): HomeCareApi
    {
        if ($this->homeCareApi === null) {
            $this->homeCareApi = new HomeCareApi(new Client(), $this->config);
        }
        return $this->homeCareApi;
    }

    public function telemedicine(): TelemedicineApi
    {
        if ($this->telemedicineApi === null) {
            $this->telemedicineApi = new TelemedicineApi(new Client(), $this->config);
        }
        return $this->telemedicineApi;
    }

    public function wellbeing(): WellbeingApi
    {
        if ($this->wellbeingApi === null) {
            $this->wellbeingApi = new WellbeingApi(new Client(), $this->config);
        }
        return $this->wellbeingApi;
    }

    public function partners(): PartnersApi
    {
        if ($this->partnersApi === null) {
            $this->partnersApi = new PartnersApi(new Client(), $this->config);
        }
        return $this->partnersApi;
    }

    public function lookup(): LookupApi
    {
        if ($this->lookupApi === null) {
            $this->lookupApi = new LookupApi(new Client(), $this->config);
        }
        return $this->lookupApi;
    }
}
