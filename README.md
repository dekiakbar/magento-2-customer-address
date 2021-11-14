# Mage2 Module Deki CustomerAddress

    ``deki/module-customeraddress``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)


## Main Functionalities
customer city and postcode autocomplete 

## Installation

### Type 1: Zip file

 - Unzip the zip file in `app/code/Deki`
 - Enable the module by running `php bin/magento module:enable Deki_CustomerAddress`
 - Apply database updates by running `php bin/magento setup:upgrade`
 - Flush the cache by running `php bin/magento cache:flush`

### Type 2: Composer
 - Install the module composer by running `composer require deki/module-customeraddress`
 - enable the module by running `php bin/magento module:enable Deki_CustomerAddress`
 - apply database updates by running `php bin/magento setup:upgrade`
 - Flush the cache by running `php bin/magento cache:flush`