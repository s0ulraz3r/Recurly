<?php
require_once('/var/www/html/recurly-client-php/lib/recurly/resource.php');
require_once('/var/www/html/recurly-client-php/lib/recurly/account.php');
require_once('/var/www/html/recurly-client-php/lib/recurly/subscription.php');
require_once('/var/www/html/recurly-client-php/lib/recurly/billing_info.php');
/**
 * Class Recurly_Purchase
 * @property Recurly_Account $account The account for the purchase. Can create an account or use existing.
 * @property Recurly_Adjustment[] $adjustments The array of adjustments for the purchase
 * @property string $collection_method The invoice collection method ('automatic' or 'manual')
 * @property string $currency The currency to use in this invoice
 * @property string $po_number The po number for the invoice
 * @property integer $net_terms The net terms of the invoice
 * @property string[] $coupon_codes An array of coupon codes to apply to the purchase
 * @property Recurly_Subscription[] $subscriptions An array of subscriptions to apply to the purchase
 * @property Recurly_GiftCard $gift_card A gift card to apply to the purchase
 * @property string $customer_notes Optional notes field. This will default to the Customer Notes text specified on the Invoice Settings page in your Recurly admin. Custom notes made on an invoice for a one time charge will not carry over to subsequent invoices.
 * @property string $terms_and_conditions Optional Terms and Conditions field. This will default to the Terms and Conditions text specified on the Invoice Settings page in your Recurly admin. Custom notes will stay with a subscription on all renewals.
 * @property string $vat_reverse_charge_notes Optional VAT Reverse Charge Notes only appear if you have EU VAT enabled or are using your own Avalara AvaTax account and the customer is in the EU, has a VAT number, and is in a different country than your own. This will default to the VAT Reverse Charge Notes text specified on the Tax Settings page in your Recurly admin, unless custom notes were created with the original subscription. Custom notes will stay with a subscription on all renewals.
 * @property integer $shipping_address_id Optional id of an existing ShippingAddress to be applied to all subscriptions and adjustments in purchase.
 * @property string $gateway_code Optional base36 encoded id for the gateway you wish to use for this transaction.
 */
class Recurly_Purchase extends Recurly_Resource
{
  /**
   * Send the purchase data to the server and creates an invoice.
   *
   * @param $purchase Recurly_Purchase Our purchase data.
   * @param Recurly_Client $client Optional client for the request, useful for mocking the client
   * @return object Recurly_InvoiceCollection
   * @throws Recurly_Error
   */
  public static function invoice($purchase = null, $client = null) {
    $purchase = new Recurly_Purchase();
    $purchase->currency = 'USD';
    $purchase->gateway_code = 'k2x3yufu70q3';
    $purchase->collection_method = 'automatic';
    $purchase->customer_notes = 'Customer Notes';
    $purchase->terms_and_conditions = 'Terms and Conditions';
    $purchase->vat_reverse_charge_notes = 'VAT Reverse Charge Notes';
    $purchase->account = new Recurly_Account();
    $purchase->account->account_code = '1212';
    $purchase->account->first_name = "TESt FinalN";
    $purchase->account->last_name = "TESt LN";
    // $purchase->account->address->phone = "555-555-5555";
    $purchase->account->email = "verena@example.com";
    $purchase->account->company_name = "XYZ";
    // $purchase->account->address->address1 = "123 Main St.";
    // $purchase->account->address->city = "San Francisco";
    // $purchase->account->address->state = "CA";
    // $purchase->account->address->zip = "94110";
    // $purchase->account->address->country = "US";
    $purchase->account->billing_info = new Recurly_BillingInfo();
    // $purchase->account->billing_info->token_id = '7z6furn4jvb9';
    // $purchase->account->billing_info->first_name = "";
    // $purchase->account->billing_info->last_name = "";
    // $purchase->account->billing_info->name_on_account = "";
    $purchase->account->billing_info->company = "XYZ";
    $purchase->account->billing_info->email = "verena@example.com";
    // $purchase->account->billing_info->ip_address = "";
    $purchase->account->billing_info->address1 = "123 Main St.";
    $purchase->account->billing_info->city = "San Francisco";
    $purchase->account->billing_info->state = "CA";
    $purchase->account->billing_info->country = "US";
    $purchase->account->billing_info->zip = "94110";
    $purchase->account->billing_info->phone = "555-555-5555";
    $purchase->account->billing_info->number = "4111-1111-1111-1111";
    $purchase->account->billing_info->month = "12";
    $purchase->account->billing_info->year = "2019";
    $purchase->account->billing_info->verification_value = "123";



    

    // $shipping_address = new Recurly_ShippingAddress();
    // $shipping_address->first_name = 'Dolores';
    // $shipping_address->last_name = 'Du Monde';
    // $shipping_address->address1 = '400 Dolores St';
    // $shipping_address->city = 'San Francisco';
    // $shipping_address->state = 'CA';
    // $shipping_address->country = 'US';
    // $shipping_address->zip = '94110';
    // $shipping_address->nickname = 'Home';
    // $purchase->account->shipping_addresses = array($shipping_address);




    // $adjustment = new Recurly_Adjustment();
    // $adjustment->product_code = "abcd123";
    // $adjustment->unit_amount_in_cents = 1000;
    // $adjustment->currency = 'USD';
    // $adjustment->quantity = 1;
    // $adjustment->revenue_schedule_type = 'at_invoice';

    // $purchase->adjustments[] = $adjustment;

    $subscription = new Recurly_Subscription();
    $subscription->plan_code = 'monthly';

    // $purchase->subscriptions = new Recurly_Subscription();
    // $purchase->subscriptions-> = "monthly";
    $purchase->subscriptions = array($subscription);
    var_dump($purchase->xml());
    return Recurly_Base::_post('/purchases', $purchase->xml(), $client);
    // echo("\n" . 'GGS' . "\n");
  }

  /**
   * Send the purchase data to the server and create a preview invoice. This runs
   * the validations but not the transactions.
   *
   * @param $purchase Recurly_Purchase Our purchase data.
   * @param Recurly_Client $client Optional client for the request, useful for mocking the client
   * @return object Recurly_InvoiceCollection
   * @throws Recurly_Error
   */
  public static function preview($purchase, $client = null) {
    return Recurly_Base::_post('/purchases/preview', $purchase->xml(), $client);
  }

  /**
   * Send the purchase data to the server and create an authorized purchase. This runs
   * the validations but not the transactions. This endpoint will create a
   * pending purchase that can be activated at a later time once payment
   * has been completed on an external source (e.g. Adyen's Hosted
   * Payment Pages).
   *
   * @param $purchase Recurly_Purchase Our purchase data.
   * @param Recurly_Client $client Optional client for the request, useful for mocking the client
   * @return object Recurly_InvoiceCollection
   * @throws Recurly_Error
   *
   * Note: To use this endpoint, you may have to contact Recurly support to have it enabled on your subdomain.
   */
  public static function authorize($purchase, $client = null) {
    return Recurly_Base::_post('/purchases/authorize', $purchase->xml(), $client);
  }

  /**
   * Capture an open Authorization request
   *
   * @param $transactionUUID string To get this uuid, do something like: $invoiceCollection->charge_invoice->transactions->current()->uuid;.
   * @param Recurly_Client $client Optional client for the request, useful for mocking the client
   * @return object Recurly_InvoiceCollection
   * @throws Recurly_Error
   *
   * Note: To use this endpoint, you may have to contact Recurly support to have it enabled on your subdomain.
   */
  public static function capture($transactionUUID, $client = null) {
    return Recurly_Base::_post('/purchases/transaction-uuid-' . rawurlencode($transactionUUID) . '/capture', null, $client);
  }

  /**
   * Cancel an open Authorization request
   *
   * @param $transactionUUID string To get this uuid, do something like: $invoiceCollection->charge_invoice->transactions->current()->uuid;.
   * @param Recurly_Client $client Optional client for the request, useful for mocking the client
   * @return object Recurly_InvoiceCollection
   * @throws Recurly_Error
   *
   * Note: To use this endpoint, you may have to contact Recurly support to have it enabled on your subdomain.
   */
  public static function cancel($transactionUUID, $client = null) {
    return Recurly_Base::_post('/purchases/transaction-uuid-' . rawurlencode($transactionUUID) . '/cancel', null, $client);
  }

  /**
   * Use for Adyen HPP transaction requests. This runs
   * the validations but not the transactions.
   *
   * @param $purchase Recurly_Purchase Our purchase data.
   * @param Recurly_Client $client Optional client for the request, useful for mocking the client
   * @return object Recurly_InvoiceCollection
   * @throws Recurly_Error
   */
  public static function pending($purchase, $client = null) {
    return Recurly_Base::_post('/purchases/pending', $purchase->xml(), $client);
  }

  public function __construct($href = null, $client = null) {
    parent::__construct($href, $client);
    $this->adjustments = array();
  }

  protected function getNodeName() {
    return 'purchase';
  }
  protected function getWriteableAttributes() {
    return array(
      'account', 'adjustments', 'collection_method', 'currency', 'po_number',
      'net_terms', 'subscriptions', 'gift_card', 'coupon_codes', 'customer_notes',
      'terms_and_conditions', 'vat_reverse_charge_notes', 'shipping_address_id',
      'gateway_code'
    );
  }
}
