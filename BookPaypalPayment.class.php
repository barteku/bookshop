<?php

namespace BookShop;

use BookShop\Book;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Core\PPConfigManager;
use PayPal\Exception\PPConnectionException;
use PayPal\Rest\ApiContext;
use BookShop\Logger;

/**
 * Description of BookPaypal
 *
 * @author bartek
 */
class BookPaypalPayment {
    
    private $payment;
    private $book;
    
    
    public function __construct(Book $book){
        $this->book = $book;
        
        $this->buildPayment();
    }
    
    private function buildPayment(){
        // ### Payer
        // A resource representing a Payer that funds a payment
        // For paypal account payments, set payment method
        // to 'paypal'.
        $payer = new Payer();
        $payer->setPaymentMethod("paypal");
        
        // ### Itemized information
        // (Optional) Lets you specify item wise
        // information
        $item1 = new Item();
        $item1->setName($this->book->getTitle())
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($this->book->getPrice());
        
        $itemList = new ItemList();
        $itemList->setItems(array($item1));
        
        // ### Amount
        // Lets you specify a payment amount.
        // You can also specify additional details
        // such as shipping, tax.
        $amount = new Amount();
        $amount->setCurrency("USD")
            ->setTotal($this->book->getPrice())
        ;
        
        // ### Transaction
        // A transaction defines the contract of a
        // payment - what is the payment for and who
        // is fulfilling it. 
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription("Book store payment for ".$this->book->getTitle());
        
        // ### Redirect urls
        // Set the urls that the buyer must be redirected to after 
        // payment approval/ cancellation.
        $baseUrl = SERVICE_URL;
        
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl("$baseUrl/purchase_activate.php")
                ->setCancelUrl("$baseUrl/purchase_cancel.php");
        
        // ### Payment
        // A Payment Resource; create one using
        // the above types and intent set to 'sale'
        $this->payment = new Payment();
        $this->payment->setIntent("sale")
                ->setPayer($payer)
                ->setRedirectUrls($redirectUrls)
                ->setTransactions(array($transaction));
        
        Logger::log("New PP transaction " . $this->payment->toJSON());
        
    }
    

    public function initPayment(){
        // ### Create Payment
        // Create a payment by calling the 'create' method
        // passing it a valid apiContext.
        // (See bootstrap.php for more on `ApiContext`)
        // The return object contains the state and the
        // url to which the buyer must be redirected to
        // for payment approval
        try {
            // $cred is used by samples that include this bootstrap file
            // This piece of code simply demonstrates how you can
            // dynamically pass in a client id/secret instead of using
            // the config file. If you do not need a way to pass
            // in credentials dynamically, you can skip the
            // <Resource>::setCredential($cred) calls that
            // you see in the samples.
            $cred = self::getCred();
            
            // ### Api Context
            // Pass in a `PayPal\Rest\ApiContext` object to authenticate 
            // the call. You can also send a unique request id 
            // (that ensures idempotency). The SDK generates
            // a request id if you do not pass one explicitly. 
            $apiContext = new ApiContext($cred, 'Request' . time());
            

            //var_dump($apiContext);die;
            
            $this->payment->create($apiContext);
        } catch (PPConnectionException $ex) {
                echo "Exception: " . $ex->getMessage() . PHP_EOL;
                var_dump($ex->getData());	
                exit(1);
        }
        Logger::log("PP transaction efter execution: " . $this->payment->toJSON());
    }
    
    



    public function getRedirectUrl(){
        $redirectUrl = null;
        
        foreach($this->payment->getLinks() as $link) {
            if($link->getRel() == 'approval_url') {
                $redirectUrl = $link->getHref();
                break;
            }
        }
        
        return $redirectUrl;
    }
        
    public function getToken(){
        $redirectUrl = $this->getRedirectUrl();
        
        $parts = parse_url($redirectUrl);
        parse_str($parts['query'], $query);
        
        return $query['token'] ? $query['token'] : null;
    }
    
    public function getTransactionId(){
        return $this->payment->getId();
    }
    
    
    
    public static function executePayment($token, $payerId){
        $purchase = Purchase::findByToken($token);

        // ### Create Payment
        // Create a payment by calling the 'create' method
        // passing it a valid apiContext.
        // (See bootstrap.php for more on `ApiContext`)
        // The return object contains the state and the
        // url to which the buyer must be redirected to
        // for payment approval
        try {
            
            // $cred is used by samples that include this bootstrap file
            // This piece of code simply demonstrates how you can
            // dynamically pass in a client id/secret instead of using
            // the config file. If you do not need a way to pass
            // in credentials dynamically, you can skip the
            // <Resource>::setCredential($cred) calls that
            // you see in the samples.
            $cred = self::getCred();
            
            // ### Api Context
            // Pass in a `PayPal\Rest\ApiContext` object to authenticate 
            // the call. You can also send a unique request id 
            // (that ensures idempotency). The SDK generates
            // a request id if you do not pass one explicitly. 
            $apiContext = new ApiContext($cred, 'Request' . time());
            
            $payment = Payment::get($purchase->getPaymentId(), $apiContext);
            $execution = new PaymentExecution();
            $execution->setPayerId($payerId);
            
            try{
                Logger::log("PP payment before execution: " . $payment->toJSON());
                $payment->execute($execution, $apiContext);
                
                Logger::log("PP payment after execution: " . $payment->toJSON());
                
                if($payment->getState() == "approved" || $payment->getState() == "created"){
                    $purchase->setAsPaid($payerId);
                    return true;
                }else{
                    Logger::log("PP transaction error: " . json_encode($_GET));
                    throw new Exception("Transaction didint go through");
                }
                
            }  catch (\Exception $e){
                throw new \Exception($e->getMessage());
            }
            
            
        } catch (PPConnectionException $ex) {
                echo "Exception: " . $ex->getMessage() . PHP_EOL;
                var_dump($ex->getData());	
                exit(1);
        }
    }

    
    private static function getCred(){
        $configManager = PPConfigManager::getInstance();
        $cred = new OAuthTokenCredential(
            $configManager->get('acct1.ClientId'),
            $configManager->get('acct1.ClientSecret')
        );
        
        return $cred;
    }
}
