
# Mage2 Module Deki CustomerAddress

  

``deki/magento-2-customer-address``

  

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

- Install the module composer by running `composer require deki/magento-2-customer-address`

- enable the module by running `php bin/magento module:enable Deki_CustomerAddress`

- apply database updates by running `php bin/magento setup:upgrade`

- Flush the cache by running `php bin/magento cache:flush`

  

## Command Line

- available contry for import : `customer-address:region-list`

- import : `customer-address:region-import <country_ID>`

  

## Config

- ### Admin
	- Admin Dasboard > store > configuration > Deki > Customer Address
		- Enable Module : enable or disable this module
		- Auto Fill Postcode : if yes, postcode will be auto filled when customer select city from dropdown
		- Minimun Search Length : how many character to trigger city autocomplete
		- Force select city from dropdown : if yes, customer must select city from dropdown, if not select from dropdown, city field and postcode will forced to empty. so customer must select city from dropdown.