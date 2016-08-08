<?php
namespace Internship\SocialLogin\Helper;

use Magento\Customer\Model\Customer;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;

class SocialLogin extends AbstractHelper
{
    /**
     * @var Session
     */
    protected $_customerSession;
    /**
     * @var CustomerFactory
     */
    protected $_customerFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * SocialLogin constructor.
     * @param Session $customerSession
     * @param CustomerFactory $customerFactory
     * @param StoreManagerInterface $storeManager
     * @param Context $context
     */

    public function __construct(
        Session $customerSession,
        CustomerFactory $customerFactory,
        StoreManagerInterface $storeManager,
        Context $context
    ) {
    
        $this->_customerSession = $customerSession;
        $this->_customerFactory = $customerFactory;
        $this->_storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * Login and save with customer email
     *
     * @param \Magento\Customer\Model\Customer $customer
     * @param array $data
     */
    public function login($customer, $data)
    {
        $data = array_replace_recursive($customer->getData(),$data);
        $customer->setData($data);
        $customer->save();
        $this->_customerSession->setCustomerAsLoggedIn($customer);
    }

    /**
     * Create new Customer
     *
     * @param array $data
     */
    public function creatingAccount($data)
    {
        $customer = $this->_customerFactory->create();
        $customer->setData($data);
        $customer->save();
        $this->_customerSession->setCustomerAsLoggedIn($customer);
    }

    /**
     * Get Customer by an attribute
     *
     * @param $id
     * @param $type
     * @return \Magento\Customer\Model\Customer
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCustomers($id, $type)
    {
        $customer = $this->_customerFactory->create()
            ->getResourceCollection()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('internship_sociallogin_id', $id)
            ->addAttributeToFilter('internship_sociallogin_type', $type)
            ->getFirstItem();
        return $customer;
    }

    /**
     * Get Customer By Email
     *
     * @param $email
     * @return \Magento\Customer\Model\Customer
     */
    public function getCustomerByEmail($email)
    {
        $websiteId = $this->_storeManager->getWebsite()->getId();
        $customer = $this->_customerFactory->create()->setWebsiteId($websiteId)->loadByEmail($email);
        return $customer;
    }
}
