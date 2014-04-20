<?php

namespace BookShop;


use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use BookShop\Book;
use PPConfigManager;

/**
 * Description of Purchase
 *
 * @author bartek
 */
class Purchase {
    
    private $id;
    
    private $bookId;
    
    private $user;
    
    private $token;
    
    private $payerId;
    
    private $status;
    
    
    public function getId() {
        return $this->id;
    }

    public function getBookId() {
        return $this->bookId;
    }

    public function getUser() {
        return $this->user;
    }

    public function getToken() {
        return $this->token;
    }

    public function getPayerId() {
        return $this->payerId;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setBookId($bookId) {
        $this->bookId = $bookId;
    }

    public function setUser($user) {
        $this->user = $user;
    }

    public function setToken($token) {
        $this->token = $token;
    }

    public function setPayerId($payerId) {
        $this->payerId = $payerId;
    }

    public function setStatus($status) {
        $this->status = $status;
    }


    public static function createPurchase($bookId, $user){
        $purchase = new Purchase();
        $purchase->setBookId($bookId);
        $purchase->setUser($user);
        
        $book = new Book();
        $book->setId($bookId);
        
        $book = $book->reload();
        
        $payment = self::getPaypalPayment($book);
        
        // ### Create Payment
        // Create a payment by calling the 'create' method
        // passing it a valid apiContext.
        // (See bootstrap.php for more on `ApiContext`)
        // The return object contains the state and the
        // url to which the buyer must be redirected to
        // for payment approval
        try {
            $configManager = PPConfigManager::getInstance();

            // $cred is used by samples that include this bootstrap file
            // This piece of code simply demonstrates how you can
            // dynamically pass in a client id/secret instead of using
            // the config file. If you do not need a way to pass
            // in credentials dynamically, you can skip the
            // <Resource>::setCredential($cred) calls that
            // you see in the samples.
            $cred = new OAuthTokenCredential(
                            $configManager->get('acct1.ClientId'),
                            $configManager->get('acct1.ClientSecret'));
            
            // ### Api Context
            // Pass in a `PayPal\Rest\ApiContext` object to authenticate 
            // the call. You can also send a unique request id 
            // (that ensures idempotency). The SDK generates
            // a request id if you do not pass one explicitly. 
            $apiContext = new ApiContext($cred, 'Request' . time());
            

            //var_dump($apiContext);die;
            $payment->create($apiContext);
        } catch (PayPal\Exception\PPConnectionException $ex) {
                echo "Exception: " . $ex->getMessage() . PHP_EOL;
                var_dump($ex->getData());	
                exit(1);
        }
        
        
        // ### Get redirect url
        // The API response provides the url that you must redirect
        // the buyer to. Retrieve the url from the $payment->getLinks()
        // method
        foreach($payment->getLinks() as $link) {
            if($link->getRel() == 'approval_url') {
                $redirectUrl = $link->getHref();
                break;
            }
        }
        
        var_dump($payment);
        
    }
    
    
    
    private static function getPaypalPayment(Book $book){
        // ### Payer
        // A resource representing a Payer that funds a payment
        // For paypal account payments, set payment method
        // to 'paypal'.
        $payer = new Payer();
        $payer->setPayment_method("paypal");
        
        // ### Itemized information
        // (Optional) Lets you specify item wise
        // information
        $item1 = new Item();
        $item1->setName($book->getTitle());
            $item1->setCurrency('USD');
            $item1->setQuantity(1);
            $item1->setPrice($book->getPrice());
        
        $itemList = new ItemList();
        $itemList->setItems(array($item1));
        
        // ### Amount
        // Lets you specify a payment amount.
        // You can also specify additional details
        // such as shipping, tax.
        $amount = new Amount();
        $amount->setCurrency("USD");
        $amount->setTotal($book->getPrice());
        
        // ### Transaction
        // A transaction defines the contract of a
        // payment - what is the payment for and who
        // is fulfilling it. 
        $transaction = new Transaction();
        $transaction->setAmount($amount);
        $transaction->setItem_list($itemList);
        $transaction->setDescription("Book store payment for ".$book->getTitle());
        
        // ### Redirect urls
        // Set the urls that the buyer must be redirected to after 
        // payment approval/ cancellation.
        $baseUrl = SERVICE_URL;
        
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturn_url("$baseUrl/purchase_activate.php");
        $redirectUrls->setCancel_url("$baseUrl/purchase_cancel.php");
        
        // ### Payment
        // A Payment Resource; create one using
        // the above types and intent set to 'sale'
        $payment = new Payment();
        $payment->setIntent("sale");
        $payment->setPayer($payer);
        $payment->setRedirect_urls($redirectUrls);
        $payment->setTransactions(array($transaction));
        
        return $payment;
    }
    
}
