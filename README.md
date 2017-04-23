# Laravel True Wallet

Browse your True Wallet Transactions for Laravel Framework.

## Requirement


## Install

Install with composer
```
composer require thatphon05/true-wallet dev-master
```
add ServiceProvider
```php
Thatphon05\TrueWallet\TrueWalletServiceProvider::class,
```
add Facade
```php
'Wallet' => Thatphon05\TrueWallet\Facades\TrueWallet::class,
```
## Configuration
Publish configuration file.
```
php artisan vendor:publish --tag=truewallet
```
If you want to use SSL, set true in a config/truewallet.php
```php
return [

    /*
     * Always used SSl
     */
    'ssl' => false,

];
```
## Usage
Add True Wallet account.
```php
Wallet::setAccount($email, $password);
```
Get Transaction maximum 50 items.
```php
$trans = Wallet::getTransaction();
foreach($wallets as $key => $value) {
    echo 'reportID='.$value->reportID.' text1='.$value->text1En;
}
```
Get Deep Transaction from reportID.
```php
$wallet = Wallet::getDeepTransaction($reportID);
$decode = json_decode($wallet);
```
Get TransactionID from reportID.
```php
Wallet::getTransactionID($reportID);
//eg. 2158596589
```
if you want reset your cookie.txt, please use
```php
Wallet:resetCookie();
```
cookie.txt is available at /storage/app
