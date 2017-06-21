# Laravel True Wallet 

[![StyleCI](https://styleci.io/repos/94993288/shield?branch=master)](https://styleci.io/repos/94993288)

Browse your [True Wallet](https://wallet.truemoney.com/) Transactions for Laravel Framework.

## Install

Install with composer
```
composer require thatphon05/true-wallet dev-master
```
Add ServiceProvider to the providers in `app/config.php`
```php
Thatphon05\TrueWallet\TrueWalletServiceProvider::class,
```
if you want to use the facade, Add this code to aliases
```php
'Wallet' => Thatphon05\TrueWallet\Facades\TrueWallet::class,
```
## Configuration
Publish configuration file.
```
php artisan vendor:publish --tag=truewallet
```
If you want to use SSL, set true in a `config/truewallet.php`
```php
return [

    /*
     * Always used SSl
     */
    'ssl' => false,

];
```
## Usage

Add credentials.
```php
Wallet::setAccount($email, $password);
```
This method get Transaction maximum 50 items :
```php
$trans = Wallet::getTransaction();
foreach ($trans as $key => $value) {
    echo 'reportID='.$value->reportID.' text1='.$value->text1En;
}
```
This method get Transaction from reportID :
```php
$trans = Wallet::getDeepTransaction($reportID);
$trans = json_decode($trans);
```
Get TransactionID from reportID.
```php
Wallet::getTransactionID($reportID);
//eg. 2158596589
```
`cookie.txt` is in `storage/app` if you want reset your cookie, please use
```php
Wallet:resetCookie();
```

