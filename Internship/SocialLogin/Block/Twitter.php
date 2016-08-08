<?php
namespace Internship\SocialLogin\Block;

class Twitter extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Internship\SocialLogin\Model\Twitter\Client
     */
    protected $_clientTwitter;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * Twitter constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Internship\SocialLogin\Model\Twitter\Client $clientTwitter
     * @param \Magento\Customer\Model\Session $customerSession
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Internship\SocialLogin\Model\Twitter\Client $clientTwitter,
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
//l1qj3RdtssOCVFJrtXCElTOdb
//4BLQkeSKGimeqYX5kPWhKvKFbWiOA3kKSpypRtKMY8CDnD33cK