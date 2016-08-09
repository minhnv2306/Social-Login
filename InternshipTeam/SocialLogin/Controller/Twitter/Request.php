<?php
namespace InternshipTeam\SocialLogin\Controller\Twitter;

class Request extends \Magento\Framework\App\Action\Action
{
    /**
     *
     */
    public function execute()
    {
        $client = $this->_objectManager->create('InternshipTeam\SocialLogin\Model\Twitter\Client');
        $client->fetchRequestToken();
    }
}
