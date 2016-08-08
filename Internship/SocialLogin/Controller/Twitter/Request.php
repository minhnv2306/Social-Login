<?php
namespace Internship\SocialLogin\Controller\Twitter;

class Request extends \Magento\Framework\App\Action\Action
{
    /**
     *
     */
    public function execute()
    {
        $client = $this->_objectManager->create('Internship\SocialLogin\Model\Twitter\Client');
        $client->fetchRequestToken();
    }
}
