<?php
namespace Magenest\SocialLogin\Setup;

use Magento\Customer\Model\Customer;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

class UpgradeData implements UpgradeDataInterface
{
    private $eavSetupFactory;

    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $eavSetup->addAttribute(
            Customer::ENTITY,
            'magenest_sociallogin_id',
            array(
                'type' => 'text',
                'visible' => false,
                'required' => false,
                'user_defined' => false,
                'label' => 'Magenest Social Id',
                'system' => false
            )
        );
        $eavSetup->addAttribute(
            Customer::ENTITY,
            'magenest_sociallogin_type',
            array(
                'type' => 'text',
                'visible' => false,
                'required' => false,
                'user_defined' => false,
                'label' => 'Magenest Social Type',
                'system' => false
            )
        );
    }
}
