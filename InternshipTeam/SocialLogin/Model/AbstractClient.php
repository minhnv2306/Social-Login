<?php
namespace InternshipTeam\SocialLogin\Model;

use Magento\Backend\App\ConfigInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\HTTP\ZendClientFactory;
use Magento\Framework\UrlInterface;

/**
 * Class AbstractClient
 * @package InternshipTeam\SocialLogin\Model
 */
abstract class AbstractClient
{
    /**
     * @var string
     */
    protected $redirect_uri_path;

    /**
     * @var string
     */
    protected $path_enalbed;

    /**
     * @var string
     */
    protected $path_client_id;

    /**
     * @var string
     */
    protected $path_client_secret;

    /**
     * @var string
     */
    protected $oauth2_service_uri;

    /**
     * @var string
     */
    protected $oauth2_auth_uri;

    /**
     * @var string
     */
    protected $oauth2_token_uri;
    /**
     * @var array
     */
    protected $scope = [];

    /**
     * @var string
     */
    protected $_config;

    /**
     * @var ZendClientFactory
     */
    protected $_httpClientFactory;

    /**
     * @var UrlInterface
     */
    protected $_url;

    /**
     * @var
     */
    protected $_helperData;

    /**
     * @var null|string
     */
    protected $clientId = null;

    /**
     * @var null|string
     */
    protected $clientSecret = null;

    /**
     * @var null|string
     */
    protected $redirectUri = null;

    /**
     * @var null
     */
    protected $state = null;

    /**
     * @var
     */
    protected $token;


    public function __construct(
        ZendClientFactory $httpClientFactory,
        ConfigInterface $config,
        UrlInterface $url)
    {
        $this->_httpClientFactory = $httpClientFactory;
        $this->_config = $config;
        $this->_url = $url;
        $this->redirectUri = $this->_url->sessionUrlVar(
            $this->_url->getUrl($this->redirect_uri_path)
        );
        $this->clientId = $this->_getClientId();
        $this->clientSecret = $this->_getClientSecret();
        $this->_config = $config;
    }

    public function isEnabled()
    {
        return (bool)$this->_isEnabled();
    }

    public function getClientId()
    {
        return $this->clientId;
    }

    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    public function getScope()
    {
        return $this->scope;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setState($state)
    {
        $this->state = $state;
    }

    public function setAccessToken($token)
    {
        $this->token = $token;
    }

    public function getAccessToken()
    {
        if (empty($this->token)) {
            $this->fetchAccessToken();
        }
        return json_encode($this->token);
    }

    public function createAuthUrl()
    {
    }

    /**
     * @param $endpoint
     * @param $code
     * @param string $method
     * @param array $params
     * @return mixed
     * @throws LocalizedException
     */
    public function api($endpoint, $code, $method = 'GET', $params = array())
    {
        if (empty($this->token)) {
            $this->fetchAccessToken($code);
        }
        $url = $this->oauth2_service_uri . $endpoint;
        $method = strtoupper($method);
        $params = array_merge(array(
            'access_token' => $this->token['access_token']
        ), $params);
        $response = $this->_httpRequest($url, $method, $params);
        return $response;
    }

    /**
     * @param null $code
     */
    protected function fetchAccessToken($code = null)
    {
    }

    /**
     * @param $url
     * @param string $method
     * @param array $params
     * @return mixed
     * @throws LocalizedException
     */
    protected function _httpRequest($url, $method = 'GET', $params = array())
    {
        $client = $this->_httpClientFactory->create();
        $client->setUri($url);
        switch ($method) {
            case 'GET':
            case 'DELETE':
                $client->setParameterGet($params);
                break;
            case 'POST':
                $client->setParameterPost($params);
                break;
            default:
                throw new LocalizedException(
                    __('Required HTTP method is not supported.')
                );
        }
        $response = $client->request($method);
        $decodedResponse = json_decode($response->getBody(), true);
        if (empty($decodedResponse)) {
            $parsed_response = array();
            parse_str($response->getBody(), $parsed_response);
            $decodedResponse = json_decode(json_encode($parsed_response), true);
        }
        if ($response->isError()) {
            $status = $response->getStatus();
            if (($status == 400 || $status == 401)) {
                if (isset($decodedResponse['error']['message'])) {
                    $message = $decodedResponse['error']['message'];
                } else {
                    $message = __('Unspecified OAuth error occurred.');
                }
                throw new LocalizedException(__($message));
            } else {
                $message = sprintf(
                    __('HTTP error %d occurred while issuing request.'),
                    $status
                );
                throw new LocalizedException(__($message));
            }
        }
        return $decodedResponse;
    }

    /**
     * @return int
     */
    protected function _isEnabled()
    {
        return $this->_getStoreConfig($this->path_enalbed);
    }

    /**
     * @return string
     */
    protected function _getClientId()
    {
        return $this->_getStoreConfig($this->path_client_id);
    }

    /**
     * @return string
     */
    protected function _getClientSecret()
    {
        return $this->_getStoreConfig($this->path_client_secret);
    }

    /**
     * @param $xmlPath
     * @return mixed
     */
    protected function _getStoreConfig($xmlPath)
    {
        return $this->_config->getValue($xmlPath);
    }
}
