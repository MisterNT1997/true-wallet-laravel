<?php

namespace Thatphon05\TrueWallet;

use Illuminate\Support\Facades\Storage;
use Thatphon05\TrueWallet\Exceptions\LoginException;

/**
 * Class TrueWallet.
 */
class TrueWallet
{
    /**
     * Email for login to the True Wallet server.
     *
     * @var string
     */
    protected $email;

    /**
     * Password for login to the True Wallet server.
     *
     * @var string
     */
    protected $password;

    /**
     * Always used SSL.
     *
     * @var bool
     */
    protected $ssl;

    /**
     * Time for curl.
     *
     * @var int
     */
    protected $timeout = 40;

    /**
     * Browser User Agent.
     *
     * @var string
     */
    protected $agent = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36 Windows';

    /**
     * @var string
     */
    protected $loginUrl = 'https://wallet.truemoney.com/user/login';

    /**
     * Url for get transaction.
     *
     * @var string
     */
    protected $transUrl = 'https://wallet.truemoney.com/v1web/api/transaction_history';

    /**
     * Url for get deep transaction.
     *
     * @var string
     */
    protected $deepTransUrl = 'https://wallet.truemoney.com/v1web/api/transaction_history_detail?reportID=';

    /**
     * TrueWallet constructor.
     *
     * @param $ssl
     */
    public function __construct($ssl)
    {
        $this->ssl = $ssl;
    }

    /**
     * Set account.
     *
     * @param $email
     * @param $password
     */
    public function setAccount($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * Set browser user agent.
     *
     * @param $name
     */
    public function setUserAgent($name)
    {
        $this->agent = $name;
    }

    /**
     * Get TransactionID from reportID.
     *
     * @param $reportID
     *
     * @return null|string
     */
    public function getTransactionID($reportID)
    {
        $tid = $this->getDeepTransaction($reportID);
        $check = $this->checkDeepData($tid);
        if ($check == false) {
            return;
        }

        return $check->data->section4->column2->cell1->value;
    }

    /**
     * Get deep transaction from ReportID.
     *
     * @param $reportID
     *
     * @return null|string
     */
    public function getDeepTransaction($reportID)
    {
        $deepTrans = $this->grab($this->deepTransUrl.$reportID);
        $check = $this->checkDeepData($deepTrans);
        if ($check == false) {
            return;
        }

        return json_encode($check);
    }

    /**
     * Get transaction list maximum 50 items.
     *
     * @return string
     */
    public function getTransaction()
    {
        $trans = json_decode($this->grab($this->transUrl));

        return $trans->data->activities;
    }

    /**
     * if no this transaction id, return false.
     *
     * @param $data
     *
     * @return bool|mixed
     */
    protected function checkDeepData($data)
    {
        $data = json_decode($data);
        if (isset($data->error) == true) {
            return false;
        }

        return $data;
    }

    /**
     *  Remove a cookie file.
     */
    public function resetCookie()
    {
        Storage::disk('local')->delete('cookie.txt');
    }

    /**
     * Login and get content from True Wallet server.
     *
     * @param $url
     *
     * @throws LoginException
     *
     * @return mixed
     */
    public function grab($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->loginUrl);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->agent);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->ssl);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'email='.$this->email.'&password='.$this->password);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIESESSION, true);
        curl_setopt($ch, CURLOPT_COOKIE, 'cookie');
        curl_setopt($ch, CURLOPT_COOKIEJAR, storage_path('app/cookie.txt'));
        curl_setopt($ch, CURLOPT_COOKIEFILE, storage_path('app/cookie.txt'));
        curl_exec($ch);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, false);

        $curl = curl_exec($ch);
        if ($curl === false) {
            throw new LoginException(curl_error($curl));
        }
        if (strpos($curl, 'Whoops') !== false) {
            throw new LoginException('Can\'t login, please check the credentials.');
        }

        return $curl;
    }
}
