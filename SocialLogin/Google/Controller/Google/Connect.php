<?php
/**
 * Created by PhpStorm.
 * User: huyen
 * Date: 6/29/16
 * Time: 8:57 PM
 */
namespace SocialLogin\Google\Controller\Google;


use Magento\Framework\App\Action\Context;

class Connect extends \Magento\Framework\App\Action\Action
{
    protected $_resultPageFactory;
    protected $_logger;

    public function __construct(
        Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_logger = $logger;
        parent::__construct($context);
    }

    public function execute()
    {
        echo "hello";
        exit;
        $resultPage = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_PAGE);
        return $resultPage;
    }
}