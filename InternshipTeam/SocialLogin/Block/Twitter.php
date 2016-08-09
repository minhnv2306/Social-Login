<?php
namespace InternshipTeam\SocialLogin\Block;

class Twitter extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \InternshipTeam\SocialLogin\Model\Twitter\Client
     */
    protected $_clientTwitter;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * Twitter constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \InternshipTeam\SocialLogin\Model\Twitter\Client $clientTwitter
     * @param \Magento\Customer\Model\Session $customerSession
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \InternshipTeam\SocialLogin\Model\Twitter\Client $clientTwitter,
        \Magento\Customer\Model\Session $customerSession
    ) {
    
        $this->_clientTwitter = $clientTwitter;
        $this->_customerSession = $customerSession;
        parent::__construct($context);

    }

    /**
     *
     */
    protected function _construct()
    {
        parent::_construct();
    }

    /**
     * @return string
     */
    public function getButtonUrl()
    {
        return $this->_clientTwitter->createAuthUrl();
    }

    /**
     * @return bool
     */
    public function isTwitterEnabled()
    {
        return $this->_clientTwitter->isEnabled();
    }
}
