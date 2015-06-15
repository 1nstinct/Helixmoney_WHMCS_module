<?php
/**
 * @author 1nstinct <yura.syedin@gmail.com>
 * Date: 6/15/15
 * Time: 4:26 PM
 * @version 1.0
 */

# Required File Includes
include("../../../dbconnect.php");
include("../../../includes/functions.php");
include("../../../includes/gatewayfunctions.php");
include("../../../includes/invoicefunctions.php");

$gatewaymodule = "helixmoney"; # Enter your gateway module name here replacing template

$GATEWAY = getGatewayVariables($gatewaymodule);
if (!$GATEWAY["type"]) die("Module Not Activated"); # Checks gateway module is active before accepting callback

$pass2  = $GATEWAY['password2'];

// HTTP parameters
$request = $_REQUEST;
$amount = $request['amount'];
$currency = $request['currency'];
$operation_id = $transid = $request['operation_id']; // invoice ID = trans ID because helix API doesn't return trans ID val
$payer = $request['payer'];


// Sign
$hash=md5($request['shop_id'].':'.$request['paysystem_id'].':'.$request['operation_id'].':'.$request['amount'].':'.$request['currency'].':'.$pass2);
if(strtolower($hash) != strtolower($request['hash'])){
    echo "bad sign\n";
    exit();
}

$invoiceid = checkCbInvoiceID($operation_id,$GATEWAY["name"]); # Checks invoice ID is a valid invoice number or ends processing

checkCbTransID($transid); # Checks transaction number isn't already in the database and ends processing if it does

addInvoicePayment($invoiceid,$transid,$amount,$fee,$gatewaymodule); # Apply Payment to Invoice: invoiceid, transactionid, amount paid, fees, modulename
logTransaction($GATEWAY["name"],$_POST,"Successful"); # Save to Gateway Log: name, data array, status

if ($result == 'true') {
    echo "OK$operation_id\n";
}