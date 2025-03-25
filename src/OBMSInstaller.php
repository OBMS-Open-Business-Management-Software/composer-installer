<?php
namespace Blesta\Composer\Installer;

use Composer\Installers\BaseInstaller;

class OBMSInstaller extends BaseInstaller
{
    protected $locations = [
        'product' => 'app/Products/{$name}/',
        'paymentgateway' => 'app/PaymentGateways/{$name}/',
    ];
}