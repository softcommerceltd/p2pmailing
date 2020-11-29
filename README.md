# Magento - P2P Mailing (FedEx Cross Border) Connector
Magento :: P2P :: Synchronisation

Shipment data synchronisation between Magento2 and P2P Mailing https://www.p2pmailing.co.uk/

## Available integrations:
- REST API Client.

## Requirements
* [Magento 2.4](https://magento.com/tech-resources/download).
* [P2P Mailing (FedEx Cross Border) account](https://www.p2pmailing.co.uk/)
* PHP 7.4.0 or later

## Installation

### Install via FTP
* Download compressed file and unzip it.
* Login to your magento server and move to magento's app/code directory
`cd app/code && mkdir SoftCommerce && cd SoftCommerce && mkdir P2p` to create new directory app/code/SoftCommerce/P2p
* Upload contents to app/code/SoftCommerce/P2p directory.
* Move back to your magento root directory and execute "Post Installation" commands described bellow.

### Install via composer

Run the following command from Magento root directory:

```
composer config repositories.softcommerce-p2p vcs https://github.com/softcommerceltd/p2pmailing.git
composer require softcommerce/module-p2p
```
If you receive an error regarding php incompatibility, but you are sure your php version is compatible, then use --ignore-platform-reqs
```
composer require softcommerce/module-p2p --ignore-platform-reqs
```

### Post Installation

In production mode:
```
php bin/magento maintenance:enable
php bin/magento setup:upgrade
php bin/magento deploy:mode:set production
php bin/magento maintenance:disable
```

In development mode:
```
php bin/magento setup:upgrade
php bin/magento setup:di:compile
```

## License
Each source file included in this package is licensed under OSL 3.0.

[Open Software License (OSL 3.0)](https://opensource.org/licenses/osl-3.0.php).
Please see `LICENSE.txt` for full details of the OSL 3.0 license.

## Thanks for dropping by

<p align="center">
    <a href="https://magento.com">
        <img src="https://softcommerce.co.uk/pub/media/banner/logo.svg" width="200" alt="Soft Commerce Ltd" />
    </a>
    <br />
    <a href="https://softcommerce.co.uk/">
        https://softcommerce.co.uk/
    </a>
</p>




