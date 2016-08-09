<?php
namespace InternshipTeam\SocialLogin\Controller\Twitter;

/**
 * Class Connect
 * @package InternshipTeam\SocialLogin\Controller\Twitter
 */
class Connect extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \InternshipTeam\SocialLogin\Helper\SocialLogin
     */
    protected $_helper;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;
    /**
     * @var \InternshipTeam\SocialLogin\Model\Twitter\Client
     */
    protected $_client;

    /**
     * Connect constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \InternshipTeam\SocialLogin\Model\Twitter\Client $client
     * @param \InternshipTeam\SocialLogin\Helper\SocialLogin $helper
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \InternshipTeam\SocialLogin\Model\Twitter\Client $client,
        \InternshipTeam\SocialLogin\Helper\SocialLogin $helper
    ) {
    
        $this->_customerSession = $customerSession;
        $this->_client = $client;
        $this->_helper = $helper;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        try {
            $this->connect();
        } catch (\Exception $e) {
            $this->messageManager->addException($e, __("Twitter login failed."));
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('customer/account');
        return $resultRedirect;
    }

    /**
     *
     */
    protected function connect()
    {
        $client = $this->_client;
        $twitter = 'twitter';
        $getParams = $_GET;
        $userInfo = array_merge((array)($userInfo = $client->api('/account/verify_credentials.json',$getParams, 'GET', array('skip_status' => true))), array('email' => sprintf('%s@twitter-user.com', strtolower($userInfo['screen_name']))));
        $client->getAccessToken();
        /** Find a customer with Twitter Id */
        $customer = $this->_helper->getCustomers($userInfo['id'], $twitter);
        if ($customer->getId()) {
            $this->_customerSession->setCustomerAsLoggedIn($customer);
            return;
        }
        /** Find a customer with Twitter Email */
        $customer = $this->_helper->getCustomerByEmail($userInfo['email']);
        if ($customer->getId()) {
            $data = [
                'sociallogin_id' => $userInfo['id'],
                'sociallogin_type' => $twitter
            ];
            $this->_helper->login($customer, $data);
            return;
        }
        /**
         * If don't exist customer, create new customer with this information
         *
         */
        $name = explode(' ', $userInfo['name'], 2);
        if (count($name) > 1) {
            $firstName = $name[0];
            $lastName = $name[1];
        } else {
            $firstName = $name[0];
            $lastName = $name[0];
        }
        $data = [
            'email' => $userInfo['email'],
            'firstname' => $firstName,
            'lastname' => $lastName,
            'sendemail' => 0,
            'confirmation' => 0,
            'sociallogin_id' => $userInfo['id'],
            'sociallogin_type' => $twitter
        ];
        $this->_helper->creatingAccount($data);
        return;
    }
}
