<?php
namespace SocialLogin\Google\Block;

class Google extends \Magento\Framework\View\Element\Template
{
    protected $_clientGoogle;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context
//        $clientGoogle
    ) {
//        $this->_clientGoogle = $clientGoogle;
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
    public function chuyenHuongURL()
    {
        return "LoginGoogle";
    }

    /**
     * @return bool
     */
    public function kiemTraTrangThaiModule()
    {
        return true;
    }
}
