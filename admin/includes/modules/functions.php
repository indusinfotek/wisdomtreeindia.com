<?php
    //ini_set("include_path", '/home/abworzlc/php:' . ini_get("include_path") );
    include '../settings/constant.php'; 
    include('Mail.php');
    include('Mail/mime.php');
    include 'mimetype.php';
    
    class FUNCTIONS {
        
        /////////////////////////////////////////////////////////////////////////////////////////
        //GENERAL FUNCTIONS//////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////
        
        //Function to get current page url==========================================
        public function getpageurl(){
            $relative_path = $_SERVER['PHP_SELF'];
            $relative_path_arr = explode('/', $relative_path);
            $page_uri = $relative_path_arr[count($relative_path_arr)-1];
            return $page_uri;
        }
        //==========================================================================
        
        public function curPageURL() {
            $pageURL = 'http';
            if(isset($_SERVER["HTTPS"]))
            if ($_SERVER["HTTPS"] == "on") {
                $pageURL .= "s";
            }
            $pageURL .= "://";
            if ($_SERVER["SERVER_PORT"] != "80") {
                $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
            } else {
                $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
            }
            return $pageURL;
        }
        
        //Function to upload the file===============================================
        public function uploadFile($uid, $newfilename, $foldername, $id){
            $allowedExts = array("jpeg", "jpg", "png");
            $temp = explode(".", $_FILES[$id]["name"]);
            $extension = end($temp);
            //$rand = uniqid('CHILD-',true);
            if (in_array($extension, $allowedExts)) {
                move_uploaded_file($_FILES[$id]["tmp_name"], $foldername.$newfilename.'.'.$extension);
                $flag[0] = 1;
                $flag[1] = $foldername.$newfilename.'.'.$extension;
                $flag[2] = $newfilename.'.'.$extension;
                $flag[3] = $extension;
            } else {
                $msg = "Invalid file type";
                $flag[0] = 0;
            }
            return $flag;
        }
        
        public function uploadExcelFile($newfilename, $foldername, $id){
            $allowedExts = array("xls", "xlsx");
            $temp = explode(".", $_FILES[$id]["name"]);
            $extension = end($temp);
            //$rand = uniqid('CHILD-',true);
            if (in_array($extension, $allowedExts)) {
                move_uploaded_file($_FILES[$id]["tmp_name"], $foldername.$newfilename.'.'.$extension);
                $flag[0] = 1;
                $flag[1] = $foldername.$newfilename.'.'.$extension;
                $flag[2] = $newfilename.'.'.$extension;
                $flag[3] = $extension;
            } else {
                $msg = "Invalid file type";
                $flag[0] = 0;
            }
            return $flag;
        }
        
        public function sendSMS($msg, $numbers){ 
            //Your authentication key
            $authKey = "105129AgwdkjrC6d56c37a01";

            //Multiple mobiles numbers separated by comma
            $mobileNumber = $numbers;

            //Sender ID,While using route4 sender id should be 6 characters long.
            $senderId = "ALWFRE";

            //Your message to send, Add URL encoding here.
            $message = urlencode($msg);

            //Define route 
            //$route = "default";
            $route = "4";            

            // init the resource
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "http://api.msg91.com/api/sendhttp.php?sender=$senderId&route=$route&mobiles=$mobileNumber&authkey=$authKey&encrypt=&country=0&message=$message",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
              ));
            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                return "cURL Error #:" . $err;
            } else {
                return $response;
            }
        }
        public function sendSMS_old($msg, $numbers){
            //Your authentication key
            $authKey = "105129AgwdkjrC6d56c37a01";

            //Multiple mobiles numbers separated by comma
            $mobileNumber = $numbers;

            //Sender ID,While using route4 sender id should be 6 characters long.
            $senderId = "ALWFRE";

            //Your message to send, Add URL encoding here.
            $message = urlencode($msg);

            //Define route 
            //$route = "default";
            $route = "4";
            //Prepare you post parameters
            $postData = array(
                'authkey' => $authKey,
                'mobiles' => $mobileNumber,
                'message' => $message,
                'sender' => $senderId,
                'route' => $route
            );

            //API URL
            $url="https://control.msg91.com/api/sendhttp.php";

            // init the resource
            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $postData
                //,CURLOPT_FOLLOWLOCATION => true
            ));


            //Ignore SSL certificate verification
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


            //get response
            $output = curl_exec($ch);

            //Print error if any
            if(curl_errno($ch))
            {
                echo 'error:' . curl_error($ch);
            }

            curl_close($ch);

            return $output;
        }

        //==========================================================================
        
        //Function to get the clients IP Address====================================
        public function getClientIP() {
            if (isset($_SERVER)) {
                if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
                    return $_SERVER["HTTP_X_FORWARDED_FOR"];

                if (isset($_SERVER["HTTP_CLIENT_IP"]))
                    return $_SERVER["HTTP_CLIENT_IP"];

                return $_SERVER["REMOTE_ADDR"];
            }

            if (getenv('HTTP_X_FORWARDED_FOR'))
                return getenv('HTTP_X_FORWARDED_FOR');

            if (getenv('HTTP_CLIENT_IP'))
                return getenv('HTTP_CLIENT_IP');

            return getenv('REMOTE_ADDR');
        }
        //==========================================================================
        
        public function getOrderMail($orderid=NULL,$user_uid=NULL){
            $order = $this->getOrders($user_uid, $orderid, NULL);
            $user = $this->getUser($user_uid, NULL, NULL, NULL, NULL);
            $user_address = $this->getUserAddresses($user_uid, $order['shippingaddress'][0], NULL, NULL, NULL, NULL, NULL);
            $state = $this->getstate(1, $user_address['state'][0], NULL);
            $country = $this->getcountry($user_address['country'][0], NULL, NULL, NULL, NULL);
            $deliveryslot = $this->getDeliverySlots($order['deleveryslot'][0]);
            
                    
            $od_id = $order['orderid'][0];
            $od_uid = $order['userid'][0];
            $od_uname = $user['fname'][0].' '.$user['lname'][0];
            $od_uemail = $user['email'][0];
            $od_shippingaddress = 'C/O '.$user_address['contact_person'][0].' [Mob: '.$user_address['contact_num'][0].']<br/>'
                    .$user_address['address_line'][0].' '
                    .$user_address['landmark'][0].' '
                    .$user_address['city'][0].' '
                    .$state['state_name'][0].' '                    
                    .$user_address['pincode'][0].' '
                    .$country['country_name'][0];
            $od_amt = $order['orderamount'][0];
            $od_shippingcharges = $order['shippingcharges'][0];
            $od_xiocash = $order['xiocashused'][0];
            $od_discount = $order['discount'][0];
            $od_payableamt = $order['payableamount'][0];
            $od_coupon = $order['couponcode'][0];
            $od_orderdt = $order['orderdate'][0];
            $od_deliverydt = $order['deliverydate'][0];
            $od_deliveryslot = $deliveryslot['display'][0];
            $od_paymentmode = ($order['paymentmode'][0]==1)?'COD':'Online Payment';
            
            $item_html = "<table>"
                    .       "<tr>"
                    .           "<td>PID<td>"
                    .           "<td>ITEM NAME<td>"
                    .           "<td>MRP<td>"
                    .           "<td>SELLING PRICE<td>"
                    .           "<td>QTY<td>"
                    .           "<td>WEIGHT<td>"
                    .       "<tr>";
            $items = (array) json_decode($order['items'][0],true);
            for($j=0;$j<count($items['items']);$j++){
                $i_pid = $items['items'][$j]['pid'];
                $i_title = $items['items'][$j]['title'];
                $i_mrp = $items['items'][$j]['mrp'];
                $i_sp = $items['items'][$j]['sellingprice'];
                $i_qty = $items['items'][$j]['qty'];
                $i_wt = $items['items'][$j]['weight'];
                
                $item_html .=    "<tr>"
                            .       "<td>$i_pid</td>"
                            .       "<td>$i_title</td>"
                            .       "<td>$i_mrp</td>"
                            .       "<td>$i_sp</td>"
                            .       "<td>$i_qty</td>"    
                            .       "<td>$i_wt</td>"
                            .   "<tr>";
            }
            
            $item_html.="</table>";
            
            $html =     "<table>"
                    .       "<tbody>"
                    .           "<tr>"
                    .               "<td>Order Id</td>"
                    .               "<td>:</td>"
                    .               "<td>$od_id</td>"
                    .           "</tr>"
                    .           "<tr>"
                    .               "<td>User Id</td>"
                    .               "<td>:</td>"
                    .               "<td>$od_uid</td>"
                    .           "</tr>"
                    .           "<tr>"
                    .               "<td>User's Name</td>"
                    .               "<td>:</td>"
                    .               "<td>$od_uname</td>"
                    .           "</tr>"
                    .           "<tr>"
                    .               "<td>User's Email</td>"
                    .               "<td>:</td>"
                    .               "<td>$od_uemail</td>"
                    .           "</tr>"
                    .           "<tr>"
                    .               "<td>Shipping Address</td>"
                    .               "<td>:</td>"
                    .               "<td>$od_shippingaddress</td>"
                    .           "</tr>"
                    .           "<tr>"
                    .               "<td>Order Amount</td>"
                    .               "<td>:</td>"
                    .               "<td>Rs. $od_amt</td>"
                    .           "</tr>"
                    .           "<tr>"
                    .               "<td>Shipping Charges</td>"
                    .               "<td>:</td>"
                    .               "<td>Rs. $od_shippingcharges</td>"
                    .           "</tr>"
                    .           "<tr>"
                    .               "<td>Discount</td>"
                    .               "<td>:</td>"
                    .               "<td>Rs. $od_discount</td>"
                    .           "</tr>"
                    .           "<tr>"
                    .               "<td>Payable Amount</td>"
                    .               "<td>:</td>"
                    .               "<td>Rs. $od_payableamt</td>"
                    .           "</tr>"
                    .           "<tr>"
                    .               "<td>Coupon Code</td>"
                    .               "<td>:</td>"
                    .               "<td>$od_coupon</td>"
                    .           "</tr>"
                    .           "<tr>"
                    .               "<td>Order Date</td>"
                    .               "<td>:</td>"
                    .               "<td>$od_orderdt</td>"
                    .           "</tr>"
                    .           "<tr>"
                    .               "<td>Delivery Date</td>"
                    .               "<td>:</td>"
                    .               "<td>$od_deliverydt</td>"
                    .           "</tr>"
                    .           "<tr>"
                    .               "<td>Delivery Slot</td>"
                    .               "<td>:</td>"
                    .               "<td>$od_deliveryslot</td>"
                    .           "</tr>"
                    .           "<tr>"
                    .               "<td>Payment Mode</td>"
                    .               "<td>:</td>"
                    .               "<td>$od_paymentmode</td>"
                    .           "</tr>"
                    .       "</tbody>"
                    .   "</table>"
                
                    .   $item_html;
            return $html;
        }
        
        public function getOrderConfirmationMail($orderid=NULL,$user_uid=NULL){
            $order = $this->getOrders($user_uid, $orderid, NULL);
            $user = $this->getUser($user_uid, NULL, NULL, NULL, NULL);
            $user_address = $this->getUserAddresses($user_uid, $order['shippingaddress'][0], NULL, NULL, NULL, NULL, NULL);
            $state = $this->getstate(1, $user_address['state'][0], NULL);
            $country = $this->getcountry($user_address['country'][0], NULL, NULL, NULL, NULL);
            $deliveryslot = $this->getDeliverySlots($order['deleveryslot'][0]);
            
                    
            //$od_id = $order['orderid'][0];
            //$od_uid = $order['userid'][0];
            $od_ufname = ucfirst(strtolower($user['fname'][0]));
            $od_uname = $user['fname'][0].' '.$user['lname'][0];
            $od_uemail = $user['email'][0];
            $od_mobile = $user['mobile'][0];
            $od_shippingaddress = 'C/O '.strtoupper($user_address['contact_person'][0]).'<br/>'
                    .strtoupper($user_address['address_line'][0]).'<br/>'
                    .strtoupper($user_address['landmark'][0]).'<br/>'
                    .strtoupper($user_address['city'][0]).' '
                    .strtoupper($state['state_name'][0]).'-'                    
                    .$user_address['pincode'][0].'<br/>'
                    .strtoupper($country['country_name'][0]);
            $od_amt = $order['orderamount'][0];
            $od_shippingcharges = $order['shippingcharges'][0];
            $od_xiocash = $order['xiocashused'][0];
            $od_discount = $order['discount'][0];
            $od_payableamt = $order['payableamount'][0];
            //$od_coupon = $order['couponcode'][0];
            $od_orderdt = date('F d, Y',strtotime($order['orderdate'][0]));
            $od_deliverydt = date('F d, Y', strtotime($order['deliverydate'][0]));
            $od_deliveryslot = $deliveryslot['display'][0];
            $od_paymentmode = ($order['paymentmode'][0]==1)?'COD':'Online Payment';
            
            $html = '<table width="600" bgcolor="#f6f8f1" border="0" cellpadding="0" cellspacing="0" style="width: 600px; margin: 0px auto; font-weight: 400; border: 1px solid #efefef; border-radius: 9px 9px 0 0; border-collapse: inherit;">
			<tr>
                            <td colspan="2" style="text-align: center; border-radius: 8px 8px 0 0; background-color: #b2c64b; padding: 5px 0;">
                                <img src="http://xioshop.com/mobile/_assets/img/xioshop-logo-color-min.png" width="180" style="width:180px;" />
                            </td>
			</tr>
			<tr>
                            <td colspan="2" style="text-align: center; font-weight: 400; text-transform: uppercase; font-size: 12px; padding: 5px 0; background-color: #202020; color: #fbfbfb;">
                                One Place. Your Place.
                            </td>
			</tr>
			<tr>
                            <td colspan="2" style="font-size: 13px; font-weight:400; background-color: #fff; padding: 8px 10px;">
                                <p>
                                    <div>Hello'.(!empty($od_ufname)?' '.$od_ufname:'').',</div>
                                </p>
                                <p>&nbsp;</p>
                                <p>Thank you for showing your interest in Xio Shop. Here is a summary of your recent order made on '.$od_orderdt.'. You can also view your order in the <span style="font-weight: 700;"><a href="http://www.xioshop.com/mobile/my-orders.php" target="_blank" style="text-decoration:none; color:#202020; cursor: pointer;">My Orders</a></span> section of your XioShop account.<br/><br/>Keep shopping.<br/><br/>Regards,<br/>Team <a href="http://www.xioshop.com" target="_blank" style="text-decoration:none; color: #202020;">Xio Shop</a></p>
                                <p>&nbsp;</p>
                                <p style="font-size: 10px; font-weight: 700;">ORDER ID: '.$orderid.'<br/>ORDER DATE: '.$od_orderdt.'<br/><br/>DELIVERY DATE: '.$od_deliverydt.'<br/>DELIVERY SLOT: '.$od_deliveryslot.'</p>
                                <p style="text-align: right;">
                                    <a href="http://www.xioshop.com/mobile/order-details.php?orderid='.$orderid.'" target="_blank" style="text-align: center; vertical-align: middle; text-decoration: none; padding: 10px 18px; border-radius: 3px; color: #fff; background-color: #202020;">Order Details</a>
                                </p>
                            </td>
			</tr>
			<tr>
                            <td style="font-size: 13px; font-weight:400; background-color: #f6f6f7; padding: 0px 10px;">
                                <p style="margin-bottom:0; font-weight:700;">Shipping Details</p>
                            </td>
                            <td style="font-size: 13px; font-weight:400; background-color: #f6f6f7; padding: 0px 10px;">
                                <p style="margin-bottom:0; font-weight:700;">Payment Details</p>
                            </td>
			</tr>
			<tr>
                            <td valign="top" style="vertical-align: top; font-size: 13px; font-weight:400; background-color: #f6f6f7; padding: 10px;">
                                <p style="margin:5px 0 0 0;">'.$od_shippingaddress.'</p>
                                <p style="margin:5px 0 0 0;">Email: '.$od_uemail.'<br/>Mobile: '.$od_mobile.'</p>
                            </td>
                            <td valign="top" style="vertical-align: top; font-size: 13px; font-weight:400; background-color: #f6f6f7; padding: 10px;">
                                <p style="margin:5px 0 0 0;">Mode: '.$od_paymentmode.'</p>
                                <p style="margin:5px 0 0 0;">Amount: Rs. '.$od_payableamt.'</p>
                            </td>
			</tr>
			<tr><td colspan="2" style="font-size: 13px; font-weight:400; background-color: #fff; padding: 0px 10px;"><p style="border-bottom: 1px solid #efefef;"></p></td></tr>
			<tr>
                            <td colspan="2" style="font-size: 13px; font-weight:400; background-color: #fff; padding: 0px 10px;">
                                <p style="background-color: #fff;">
                                    <table border="0" cellpadding="0" cellspacing="0" bgcolor="#fff" style="width:100%; background-color: #fff;">
                                        <tr style="font-weight: 700;" valign="top">
                                            <td width="40%">ITEM</td>
                                            <td width="20%" style="text-align: right;">PRICE</td>
                                            <td width="20%" style="text-align: right;">QTY</td>
                                            <td width="20%" style="text-align: right;">SUBTOTAL</td>
                                        </tr>';
                                $items = (array) json_decode($order['items'][0],true);
                                for($j=0;$j<count($items['items']);$j++){
                                    $i_pid = $items['items'][$j]['pid'];
                                    $i_title = $items['items'][$j]['title'];
                                    //$i_mrp = $items['items'][$j]['mrp'];
                                    $i_sp = $items['items'][$j]['sellingprice'];
                                    $i_qty = $items['items'][$j]['qty'];
                                    $i_wt = $items['items'][$j]['weight'];
                                $html.=	'<tr style="font-size:11px;" valign="top">
                                            <td width="40%">'.$i_title.' ('.$i_wt.')</td>
                                            <td width="20%" style="text-align: right;">Rs.'.$i_sp.'</td>
                                            <td width="20%" style="text-align: right;">'.$i_qty.'</td>
                                            <td width="20%" style="text-align: right;">Rs.'.($i_sp*$i_qty).'</td>
                                        </tr>';
                                }
                                $html.=	'<tr style="font-size:11px; font-weight: 700;" valign="top">
                                            <td colspan="3" style="text-align: right;">TOTAL</td>
                                            <td style="text-align: right;">Rs.'.$od_amt.'</td>
                                        </tr>
                                        <tr style="font-size:11px; font-weight: 700;" valign="top">
                                            <td colspan="3" style="text-align: right;">SHIPPING CHARGES</td>
                                            <td style="text-align: right;">Rs.'.$od_shippingcharges.'</td>
                                        </tr>
                                        <tr style="font-size:11px; font-weight: 700;" valign="top">
                                            <td colspan="3" style="text-align: right;">XIO CASH</td>
                                            <td style="text-align: right;">Rs.'.$od_xiocash.'</td>
                                        </tr>
                                        <tr style="font-size:11px; font-weight: 700;" valign="top">
                                            <td colspan="3" style="text-align: right;">DISCOUNT</td>
                                            <td style="text-align: right;">Rs.'.$od_discount.'</td>
                                        </tr>
                                        <tr style="font-size:11px; font-weight: 700; background-color: #efefef;" valign="top">
                                            <td colspan="3" style="text-align: right; padding: 3px 0">PAYABLE AMOUNT</td>
                                            <td style="text-align: right; padding: 3px 0">Rs.'.$od_payableamt.'</td>
                                        </tr>
                                    </table>
                                </p>
                            </td>
			</tr>
			<tr><td colspan="2" style="font-size: 13px; font-weight:400; background-color: #fff; padding: 0px 10px;"><p style="border-bottom: 1px solid #efefef;"></p></td></tr>
			<tr>
			<td colspan="2" style="text-align: center; font-size: 11px; font-weight:400; background-color: #fff; padding: 0px 10px;">
                            <p style="background-color: #202020; color: #fbfbfb; padding: 3px; margin:0;">&copy; 2016-17 Xio Shop. All rights reserved.</p>
			</td>			
			</tr>
			<tr>
                            <td colspan="2" style="text-align: center; font-size: 10px; font-weight:400; background-color: #fff; padding: 0px 10px;">
                                <p style="padding: 3px; margin:0;">NEW NO. R-27 OLD NO. R-23C, THIRD FLOOR, EAST VINOD NAGAR, GAUTAM MARG, DELHI-110091, INDIA.<br/>PAN: AAAFX2107R | Service Tax: AAAFX2107RSD001</p>
                            </td>
			</tr>
			<tr><td colspan="2" style="font-size: 13px; font-weight:400; background-color: #fff; padding: 0px 10px;"><p style="border-bottom: 1px solid #efefef;"></p></td></tr>
                    </table>';
            return $html;
        }
        
        public function getInvoice($orderid=NULL,$user_uid=NULL){
            $order = $this->getOrders($user_uid, $orderid, NULL);
            $user = $this->getUser($user_uid, NULL, NULL, NULL, NULL);
            $user_address = $this->getUserAddresses($user_uid, $order['shippingaddress'][0], NULL, NULL, NULL, NULL, NULL);
            $state = $this->getstate(1, $user_address['state'][0], NULL);
            $country = $this->getcountry($user_address['country'][0], NULL, NULL, NULL, NULL);
            $deliveryslot = $this->getDeliverySlots($order['deleveryslot'][0]);

            $usr_fname = ucfirst(strtolower($user['fname'][0]));
            $usr_lname = ucfirst(strtolower($user['lname'][0]));
            $usr_mobile = $user['mobile'][0];
            $usr_email = strtolower($user['email'][0]); 

            $od_ufname = ucfirst(strtolower($user['fname'][0]));
            $od_uname = $user['fname'][0].' '.$user['lname'][0];
            $od_uemail = $user['email'][0];
            $od_mobile = $user['mobile'][0];
            $od_shippingaddress = strtoupper($user_address['address_line'][0]).'<br/>'
                    .strtoupper($user_address['landmark'][0]).'<br/>'
                    .strtoupper($user_address['city'][0]).' '
                    .strtoupper($state['state_name'][0]).'-'                    
                    .$user_address['pincode'][0].'<br/>'
                    .strtoupper($country['country_name'][0]);
            $od_amt = $order['orderamount'][0];
            $od_shippingcharges = $order['shippingcharges'][0];
            $od_xiocash = $order['xiocashused'][0];
            $od_discount = $order['discount'][0];
            $od_payableamt = $order['payableamount'][0];
            //$od_coupon = $order['couponcode'][0];
            $od_orderdt = date('F d, Y',strtotime($order['orderdate'][0]));
            $od_deliverydt = date('F d, Y', strtotime($order['deliverydate'][0]));
            $od_deliveryslot = $deliveryslot['display'][0];
            $od_paymentmode = ($order['paymentmode'][0]==1)?'COD':'Online Payment';

            $invoice_num = date('Y-m-d', strtotime($od_orderdt)).'-'.str_pad($order['id'][0], 5, "0", STR_PAD_LEFT);

            $html = '<table width="600" bgcolor="#f6f8f1" border="0" cellpadding="0" cellspacing="0" style="width: 600px; margin: 0px auto; font-weight: 400; border: 1px solid #efefef; border-radius: 9px 9px 0 0; border-collapse: inherit;">
                        <tr>
                            <td colspan="2" style="text-align: center; border-radius: 8px 8px 0 0; background-color: #b2c64b; padding: 5px 0;">
                                <img src="http://xioshop.com/mobile/_assets/img/xioshop-logo-color-min.png" width="180" style="width:180px;" />
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: center; font-weight: 400; text-transform: uppercase; font-size: 12px; padding: 5px 0; background-color: #202020; color: #fbfbfb;">
                                One Place. Your Place.
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="font-size: 13px; font-weight:400; background-color: #fff; padding: 8px 10px;">
                                <p><h2 style="text-align: center;">INVOICE</h2></p>	

                                <p>
                                    <div style="float:left; width:48%;">
                                        <h4 style="border-bottom: 1px solid #efefef; padding-bottom: 5px;">&nbsp;<br/>Invoiced To</h4>
                                        <p>
                                            ATTN: '.$usr_fname.' '.$usr_lname.'<br/>
                                            '.$od_shippingaddress.'<br/><br/>

                                            MOBILE: '.$usr_mobile.'<br/>
                                            EMAIL: '.$usr_email.'
                                        </p>
                                    </div>
                                    <div style="float:right; width:48%; text-align: right;">
                                        <h4 style="border-bottom: 1px solid #efefef; padding-bottom: 5px;">INVOICE#: '.$invoice_num.'<br/>INVOICE DATE: '.$od_orderdt.'</h4>
                                        <p>
                                            M/S XIO SHOP<br/>
                                            NEW NO. R-27 OLD NO. R-23C,<br/>
                                            THIRD FLOOR, EAST VINOD NAGAR,<br/>
                                            GAUTAM MARG, DELHI-110091,<br/>
                                            INDIA.<br/><br/>

                                            PAN: AAAFX2107R<br/>
                                            STN: AAAFX2107RSD001
                                        </p>
                                    </div>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 13px; font-weight:400; background-color: #f6f6f7; padding: 0px 10px;">
                                <p style="margin-bottom:0; font-weight:700;">Order Details</p>
                            </td>
                            <td style="font-size: 13px; font-weight:400; background-color: #f6f6f7; padding: 0px 10px;">
                                <p style="margin-bottom:0; font-weight:700;">Payment Details</p>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" style="vertical-align: top; font-size: 13px; font-weight:400; background-color: #f6f6f7; padding: 10px;">
                                <p style="margin:5px 0 0 0;">
                                    ORDER ID: '.$orderid.'<br/>
                                    ORDER DATE: '.$od_orderdt.'<br/><br/>

                                    DELIVERY DATE: '.$od_deliverydt.'<br/>
                                    DELIVERY SLOT: '.$od_deliveryslot.'
                                </p>
                            </td>
                            <td valign="top" style="vertical-align: top; font-size: 13px; font-weight:400; background-color: #f6f6f7; padding: 10px;">
                                <p style="margin:5px 0 0 0;">Mode: '.$od_paymentmode.'</p>
                                <p style="margin:5px 0 0 0;">Amount: Rs. '.$od_payableamt.'</p>
                            </td>
                        </tr>
                        <tr><td colspan="2" style="font-size: 13px; font-weight:400; background-color: #fff; padding: 0px 10px;"><p style="border-bottom: 1px solid #efefef;"></p></td></tr>
                        <tr>
                            <td colspan="2" style="font-size: 13px; font-weight:400; background-color: #fff; padding: 0px 10px;">
                                <p style="background-color: #fff;">
                                    <table border="0" cellpadding="0" cellspacing="0" bgcolor="#fff" style="width:100%; background-color: #fff;">
                                        <tr style="font-weight: 700;" valign="top">
                                            <td width="40%">ITEM</td>
                                            <td width="20%" style="text-align: right;">PRICE</td>
                                            <td width="20%" style="text-align: right;">QTY</td>
                                            <td width="20%" style="text-align: right;">SUBTOTAL</td>
                                        </tr>';
                                $items = (array) json_decode($order['items'][0],true);
                                for($j=0;$j<count($items['items']);$j++){
                                    $i_pid = $items['items'][$j]['pid'];
                                    $i_title = $items['items'][$j]['title'];
                                    //$i_mrp = $items['items'][$j]['mrp'];
                                    $i_sp = $items['items'][$j]['sellingprice'];
                                    $i_qty = $items['items'][$j]['qty'];
                                    $i_wt = $items['items'][$j]['weight'];
                                $html.=	'<tr style="font-size:11px;" valign="top">
                                            <td width="40%">'.$i_title.' ('.$i_wt.')</td>
                                            <td width="20%" style="text-align: right;">Rs.'.$i_sp.'</td>
                                            <td width="20%" style="text-align: right;">'.$i_qty.'</td>
                                            <td width="20%" style="text-align: right;">Rs.'.($i_sp*$i_qty).'</td>
                                        </tr>';
                                }
                                $html.=	'<tr style="font-size:11px; font-weight: 700;" valign="top">
                                            <td colspan="3" style="text-align: right;">TOTAL</td>
                                            <td style="text-align: right;">Rs.'.$od_amt.'</td>
                                        </tr>
                                        <tr style="font-size:11px; font-weight: 700;" valign="top">
                                            <td colspan="3" style="text-align: right;">SHIPPING CHARGES</td>
                                            <td style="text-align: right;">Rs.'.$od_shippingcharges.'</td>
                                        </tr>
                                        <tr style="font-size:11px; font-weight: 700;" valign="top">
                                            <td colspan="3" style="text-align: right;">XIO CASH</td>
                                            <td style="text-align: right;">Rs.'.$od_xiocash.'</td>
                                        </tr>
                                        <tr style="font-size:11px; font-weight: 700;" valign="top">
                                            <td colspan="3" style="text-align: right;">DISCOUNT</td>
                                            <td style="text-align: right;">Rs.'.$od_discount.'</td>
                                        </tr>
                                        <tr style="font-size:11px; font-weight: 700; background-color: #efefef;" valign="top">
                                            <td colspan="3" style="text-align: right; padding: 3px 0">PAYABLE AMOUNT</td>
                                            <td style="text-align: right; padding: 3px 0">Rs.'.$od_payableamt.'</td>
                                        </tr>
                                    </table>
                                </p>
                            </td>
                        </tr>
                        <tr><td colspan="2" style="font-size: 13px; font-weight:400; background-color: #fff; padding: 0px 10px;"><p style="border-bottom: 1px solid #efefef;"></p></td></tr>
                        <tr>
                        <td colspan="2" style="text-align: center; font-size: 11px; font-weight:400; background-color: #fff; padding: 0px 10px;">
                            <p style="background-color: #202020; color: #fbfbfb; padding: 3px; margin:0;">&copy; 2016-17 Xio Shop. All rights reserved.</p>
                        </td>			
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: center; font-size: 10px; font-weight:400; background-color: #fff; padding: 0px 10px;">
                                <p style="padding: 3px; margin:0;">NEW NO. R-27 OLD NO. R-23C, THIRD FLOOR, EAST VINOD NAGAR, GAUTAM MARG, DELHI-110091, INDIA.<br/>PAN: AAAFX2107R | Service Tax: AAAFX2107RSD001</p>
                            </td>
                        </tr>
                        <tr><td colspan="2" style="font-size: 13px; font-weight:400; background-color: #fff; padding: 0px 10px;"><p style="border-bottom: 1px solid #efefef;"></p></td></tr>
                    </table>';
                $data['invoice_num']=$invoice_num;
                $data['html']=$html;
            return $data;
        }
        
        public function getSignUpMail($uid){
            $user = $this->getUser($uid);
            
            $html = '<table width="600" bgcolor="#f6f8f1" border="0" cellpadding="0" cellspacing="0" style="width: 600px; margin: 0px auto;font-weight: 400; border: 1px solid #efefef; border-radius: 9px 9px 0 0;">
			<tr>
                            <td colspan="2" style="text-align: center; border-radius: 8px 8px 0 0; background-color: #b2c64b; padding: 5px 0;">
                                <img src="'.SITE_LOGO.'" width="180" />
                            </td>
			</tr>
			<tr>
                            <td colspan="2" style="text-align: center; font-weight: 400; text-transform: uppercase; font-size: 12px; padding: 5px 0; background-color: #202020; color: #fbfbfb;">
                                '.SITE_TAGLINE.'
                            </td>
			</tr>
			<tr>
                            <td colspan="2" style="font-size: 13px; font-weight:400;background-color: #fff; padding: 8px 10px;">
                                <p>Welcome! '.ucfirst(strtolower($user["fname"][0])).',</p>	

                                <p>
                                    Thanks for showing your interest in '.SITE_TITLE.' and registering with us.<br/><br/>
                                    You can now start shopping for your fruits, vegetables and various other daily needs. You can either do that simply by browsing our website <a href="'.SITE_LINK.'" target="_blank" style="text-decoration: underline;">'.SITE_URL.'</a> or you can also download our Mobile App from the <a href="'.APP_LINK.'" target="_blank" style="text-decoration: underline;">Google PlayStore</a> (currently available for Android users).<br/><br/>

                                    We request you to add all your preferred shipping address under <a href="'.BASEPATH.'my-address.php" target="_blank" style="text-decoration: underline;">My Address</a> section by simply LoggingIn to the site or app. Currently our services are limited to particular areas based on the area code (Pincode), you can check our <a href="'.BASEPATH.'serviceable-areas.php" target="_blank" style="text-decoration: underline;">Serviceable Areas</a> here. 
                                </p>
                            </td>
			</tr>
			<tr>
				<td colspan="2" style="font-size: 13px; font-weight:400;background-color: #f6f6f7; padding: 0px 10px;">
					<p style="margin-bottom:0; font-weight:700;">User/Login Details</p>
				</td>
			</tr>
			<tr>
				<td colspan="2" valign="top" style="vertical-align: top; font-size: 13px; font-weight:400;background-color: #f6f6f7; padding: 10px;">
					<p style="margin:5px 0 0 0;">
						First Name: '.ucfirst(strtolower($user["fname"][0])).'<br/>
						Last Name: '.ucfirst(strtolower($user["lname"][0])).'<br/>
						Mobile: '.$user["mobile"][0].'<br/>
						Pincode: '.$user["pincode"][0].'<br/><br/>
						Email: '.strtolower($user["email"][0]).'
					</p>
				</td>
			</tr>
			<tr><td colspan="2" style="font-size: 13px; font-weight:400;background-color: #fff; padding: 0px 10px;"><p style="border-bottom: 1px solid #efefef;"></p></td></tr>
			<tr>
                            <td colspan="2" style="font-size: 13px; font-weight:400;background-color: #fff; padding: 0px 10px;">
                                <p style="background-color: #fff;">
                                    Thanks again. Keep shopping.<br/><br/>
                                    <strong>Regards,<br/>Team '.SITE_TITLE.'</strong>
                                </p>
                                <p>&nbsp;</p>
                                <p style="font-weight: 200; font-size: 10px; color: #5e5e5e;">NOTE: If you did not create this '.SITE_TITLE.' Account and do not recognize this email, please contact us at <a href="mailto:'.SITE_SUPPORT_EMAIL.'?Subject=Unlink Account&body=Kindly unlink the following email: '.strtolower($user['email'][0]).'&cc='.COMPLIANCE_EMAIL.'" target="_top">'.SITE_SUPPORT_EMAIL.'</a> to unlink this account.</p>
                            </td>
			</tr>
			<tr><td colspan="2" style="font-size: 13px; font-weight:400;background-color: #fff; padding: 0px 10px;"><p style="border-bottom: 1px solid #efefef;"></p></td></tr>
			<tr>
			<td colspan="2" style="text-align: center; font-size: 11px; font-weight:400;background-color: #fff; padding: 0px 10px;">
                            <p style="background-color: #202020; color: #fbfbfb; padding: 3px; margin:0;">&copy; 2017-18 '.COMPANY_NAME.'. All rights reserved. Developed & Managed by <a href="http://www.abworks.in?utm_source='.urlencode(SITE_TITLE).'&utm_medium=SignUpMail" target="_blank" style="text-decoration:none;color:#fff;">AB Works</a>.</p>
			</td>			
			</tr>
			<tr>
                            <td colspan="2" style="text-align: center; font-size: 10px; font-weight:400;background-color: #fff; padding: 0px 10px;">
                                <p style="padding: 3px; margin:0;">'.COMPANY_ADDRESS.'<br/>PAN: '.COMPANY_PAN.' | Service Tax: '.COMPANY_STAX.'</p>
                            </td>
			</tr>
			<tr><td colspan="2" style="font-size: 13px; font-weight:400;background-color: #fff; padding: 0px 10px;"><p style="border-bottom: 1px solid #efefef;"></p></td></tr>
		</table>';
            return $html;
        }
        
        public function getMilkSubscriptionMail($subscriptionid){            
            $milksubscription = $this->getMilkSubscription($subscriptionid);
            $user = $this->getUser($milksubscription['uid'][0]);
            
            $start_date = date('d-M-Y', strtotime($milksubscription['start_date'][0]));
            $delivery_slot = ($milksubscription['delivery_slot'][0]==0)?'Morning':'Evening';
            $subscription_period = ($milksubscription['subscription_period'][0]==0)?'15days':'30days';
            $amul_toned_milk = $milksubscription['amul_toned_milk'][0];
            $amul_full_cream_milk = $milksubscription['amul_full_cream_milk'][0];
            $motherdairy_toned_milk = $milksubscription['motherdairy_toned_milk'][0];
            $motherdairy_full_cream_milk = $milksubscription['motherdairy_full_cream_milk'][0];
            
            $html = '<table width="600" bgcolor="#f6f8f1" border="0" cellpadding="0" cellspacing="0" style="width: 600px; margin: 0px auto;font-weight: 400; border: 1px solid #efefef; border-radius: 9px 9px 0 0;">
			<tr>
                            <td colspan="2" style="text-align: center; border-radius: 8px 8px 0 0; background-color: #b2c64b; padding: 5px 0;">
                                <img src="http://xioshop.com/mobile/_assets/img/xioshop-logo-color-min.png" width="180" />
                            </td>
			</tr>
			<tr>
                            <td colspan="2" style="text-align: center; font-weight: 400; text-transform: uppercase; font-size: 12px; padding: 5px 0; background-color: #202020; color: #fbfbfb;">
                                One Place. Your Place.
                            </td>
			</tr>
			<tr>
                            <td colspan="2" style="font-size: 13px; font-weight:400;background-color: #fff; padding: 8px 10px;">
                                <p>Welcome! '.ucfirst(strtolower($user["fname"][0])).',</p>	

                                <p>
                                    Thanks for subscribing for XioShop Milk Subscription Package.<br/><br/>
                                    Our executives will contact you shortly regarding the same and guide you with the further proceedings.<br/><br/>
                                    
                                </p>
                            </td>
			</tr>
			<tr>
                            <td colspan="2" style="font-size: 13px; font-weight:400;background-color: #f6f6f7; padding: 0px 10px;">
                                <p style="margin-bottom:0; font-weight:700;">Subscription Details</p>
                            </td>
			</tr>
			<tr>
                            <td colspan="2" valign="top" style="vertical-align: top; font-size: 13px; font-weight:400;background-color: #f6f6f7; padding: 10px;">
                                <p style="margin:5px 0 0 0;">
                                    Start Date: '.$start_date.'<br/>
                                    Delivery Slot: '.$delivery_slot.'<br/>
                                    Subscription Period: '.$subscription_period.'<br/><br/>
                                    Milk Subscription Details <br/><br/>
                                    <table>
                                        <thead>
                                            <tr>
                                                <td>Milk Type</td>
                                                <td>&nbsp;</td>
                                                <td>Qty.</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Amul Toned Milk (500ML)</td>
                                                <td>:</td>
                                                <td>'.$amul_toned_milk.'</td>
                                            </tr>
                                            <tr>
                                                <td>Amul Full Cream Milk (500ML)</td>
                                                <td>:</td>
                                                <td>'.$amul_full_cream_milk.'</td>
                                            </tr>
                                            <tr>
                                                <td>Mother Dairy Toned Milk (500ML)</td>
                                                <td>:</td>
                                                <td>'.$motherdairy_toned_milk.'</td>
                                            </tr>
                                            <tr>
                                                <td>Mother Dairy Full Cream Milk (500ML)</td>
                                                <td>:</td>
                                                <td>'.$motherdairy_full_cream_milk.'</td>
                                            </tr>
                                        </tbody>
                                    <table>
                                </p>
                            </td>
			</tr>
			<tr><td colspan="2" style="font-size: 13px; font-weight:400;background-color: #fff; padding: 0px 10px;"><p style="border-bottom: 1px solid #efefef;"></p></td></tr>
			<tr>
                            <td colspan="2" style="font-size: 13px; font-weight:400;background-color: #fff; padding: 0px 10px;">
                                <p style="background-color: #fff;">
                                    Thanks again. Keep shopping.<br/><br/>
                                    <strong>Regards,<br/>Team Xio Shop</strong>
                                </p>
                                <p>&nbsp;</p>
                                <p style="font-weight: 200; font-size: 10px; color: #5e5e5e;">NOTE: If you did not subscribe this Milk Subscription and do not recognize this email, please contact us at <a href="mailto:info@xioshop.com?Subject=Unsubscribe Milk Supply&body=Kindly unsubscribe the following email: '.strtolower($user['email'][0]).' from Xio Milk Supply.&cc=complaints@xioshop.com" target="_top">info@xioshop.com</a>.</p>
                            </td>
			</tr>
			<tr><td colspan="2" style="font-size: 13px; font-weight:400;background-color: #fff; padding: 0px 10px;"><p style="border-bottom: 1px solid #efefef;"></p></td></tr>
			<tr>
			<td colspan="2" style="text-align: center; font-size: 11px; font-weight:400;background-color: #fff; padding: 0px 10px;">
                            <p style="background-color: #202020; color: #fbfbfb; padding: 3px; margin:0;">&copy; 2016-17 Xio Shop. All rights reserved.</p>
			</td>			
			</tr>
			<tr>
                            <td colspan="2" style="text-align: center; font-size: 10px; font-weight:400;background-color: #fff; padding: 0px 10px;">
                                <p style="padding: 3px; margin:0;">NEW NO. R-27 OLD NO. R-23C, THIRD FLOOR, EAST VINOD NAGAR, GAUTAM MARG, DELHI-110091, INDIA.<br/>PAN: AAAFX2107R | Service Tax: AAAFX2107RSD001</p>
                            </td>
			</tr>
			<tr><td colspan="2" style="font-size: 13px; font-weight:400;background-color: #fff; padding: 0px 10px;"><p style="border-bottom: 1px solid #efefef;"></p></td></tr>
		</table>';
            return $html;
        }
        
        public function sendMail($maildata=NULL, $smtp=NULL){            
            $recipients =   (!empty($maildata->to)?$maildata->to.', ':'').
                            (!empty($maildata->cc)?$maildata->cc.', ':'').
                            (!empty($maildata->bcc)?$maildata->bcc.', ':'');    

            $headers['From']    = $maildata->fromname."<$maildata->from>";
            $headers['To']      = $maildata->to;
            $headers['Cc']      = $maildata->cc;
            $headers['Subject'] = $maildata->subject;
            $headers['Content-Type'] = "text/html; charset=\"UTF-8\"";
            $headers['Content-Transfer-Encoding'] = '8bit';

            $body = $maildata->message;

            // Define SMTP Parameters
            $params =  (array) $smtp;

            // Create the mail object using the Mail::factory method
            $mail_object = & Mail::factory('smtp', $params);

            // Send the message
            $result = $mail_object->send($recipients, $headers, $body);
            
            return $result;
        }
        
        /////////////////////////////////////////////////////////////////////////////////////////
        //END GENERAL FUNCTIONS//////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////
        
       
        /////////////////////////////////////////////////////////////////////////////////////////
        //USER AND ADMIN RELATED FUNCTIONS//////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////
        public function getMilkSubscription($subscriptionid){
            $condition = "";
            if(!empty($subscriptionid)){
                $condition.=empty($condition)?" subscriptionid='$subscriptionid' ":" AND subscriptionid='$subscriptionid' ";              
            }
            $condition = trim(str_replace('  ', ' ', $condition));            
            
            $sql = (!empty($condition))?
                    "SELECT * FROM `milk-subscription` WHERE $condition ":
                    "SELECT * FROM `milk-subscription`";
            //echo $sql;
            $db = new db();
            $db->connect();
            $dbname = MAINDB;
            $db->select_db($dbname);
            
            $i=0; $row = Array();
            if($db->is_connected()==true){
                $result = $db->query($sql);                
                while($resarr = mysqli_fetch_array($result,MYSQLI_BOTH)){
                    $row['id'][$i] = $resarr['id'];
                    $row['subscriptionid'][$i] = $resarr['subscriptionid'];
                    $row['uid'][$i] = $resarr['uid'];
                    $row['start_date'][$i] = $resarr['start_date'];
                    $row['delivery_slot'][$i] = $resarr['delivery_slot'];
                    $row['subscription_period'][$i] = $resarr['subscription_period'];
                    $row['amul_toned_milk'][$i] = $resarr['amul_toned_milk'];                    
                    $row['amul_full_cream_milk'][$i] = $resarr['amul_full_cream_milk'];
                    $row['motherdairy_toned_milk'][$i] = $resarr['motherdairy_toned_milk'];
                    $row['motherdairy_full_cream_milk'][$i] = $resarr['motherdairy_full_cream_milk'];
                    $row['isactive'][$i] = $resarr['isactive']; 
                    $row['ipaddress'][$i] = $resarr['ipaddress'];
                    $row['publishdate'][$i] = $resarr['publishdate']; 
                    $i++;
                }
                $row['count'] = $i;
            }else{
                echo 'Not Connected<br/>';
            }
            
            return ($row);
        }
        
        public function getUser($uid=NULL, $id=NULL, $email=NULL, $password=NULL, $isactive=NULL, $mobile=NULL, $order=NULL){
            $condition = "";
            if(!empty($uid)){
                $condition.=empty($condition)?" uid='$uid' ":" AND uid='$uid' ";              
            }
            if(!empty($email)){
                $condition.=empty($condition)?" email='$email' ":" AND email='$email' ";              
            }
            if(!empty($password)){
                $condition.=empty($condition)?" password=md5('$password') ":" AND password=md5('$password') ";              
            }
            if(!empty($id)){
                $condition.=empty($condition)?" id='$id' ":" AND id='$id' ";              
            }
            if(!empty($isactive)){
                $condition.=empty($condition)?" isactive='$isactive' ":" AND isactive='$isactive' ";              
            }
            if(!empty($mobile)){
                $condition.=empty($condition)?" mobile='$mobile' ":" AND mobile='$mobile' ";              
            }
            $condition = trim(str_replace('  ', ' ', $condition));            
            
            $sql = (!empty($condition))?
                    "SELECT * FROM `user` WHERE $condition ".((!empty($order))?"ORDER BY $order":""):
                    "SELECT * FROM `user` ".((!empty($order))?"ORDER BY $order":"");
            //echo $sql;
            $db = new db();
            $db->connect();
            $dbname = MAINDB;
            $db->select_db($dbname);
            
            $i=0; $row = Array();
            if($db->is_connected()==true){
                $result = $db->query($sql);                
                while($resarr = mysqli_fetch_array($result,MYSQLI_BOTH)){
                    $row['id'][$i] = $resarr['id'];
                    $row['uid'][$i] = $resarr['uid'];
                    $row['email'][$i] = $resarr['email'];
                    $row['password'][$i] = $resarr['password'];
                    $row['fname'][$i] = $resarr['fname'];
                    $row['lname'][$i] = $resarr['lname'];                    
                    $row['mobile'][$i] = $resarr['mobile'];
                    $row['alternate_contact_num'][$i] = $resarr['alternate_contact_num'];
                    $row['gender'][$i] = $resarr['gender'];
                    $row['pincode'][$i] = $resarr['pincode'];                    
                    $row['isactive'][$i] = $resarr['isactive'];
                    $row['ipaddress'][$i] = $resarr['ipaddress'];
                    $row['publishdate'][$i] = $resarr['publishdate']; 
                    $i++;
                }
                $row['count'] = $i;
            }else{
                echo 'Not Connected<br/>';
            }
            
            return ($row);
        }
        
        public function getAdminUser($id=NULL, $username=NULL, $email=NULL, $password=NULL, $isactive=NULL){
            $condition = "";
            if(!empty($id)){
                $condition.=empty($condition)?" id='$id' ":" AND id='$id' ";              
            }
            if(!empty($username)){
                $condition.=empty($condition)?" username='$username' ":" AND username='$username' ";              
            }
            if(!empty($email)){
                $condition.=empty($condition)?" email='$email' ":" AND email='$email' ";              
            }
            if(!empty($password)){
                $condition.=empty($condition)?" password=md5('$password') ":" AND password=md5('$password') ";              
            }
            
            if(!empty($isactive)){
                $condition.=empty($condition)?" isactive='$isactive' ":" AND isactive='$isactive' ";              
            }
            
            $condition = trim(str_replace('  ', ' ', $condition));            
            
            $sql = (!empty($condition))?
                    "SELECT * FROM `admin_user` WHERE $condition":
                    "SELECT * FROM `admin_user`";
            //echo $sql;
            $db = new db();
            $db->connect();
            $dbname = MAINDB;
            $db->select_db($dbname);
            
            $i=0; $row = Array();
            if($db->is_connected()==true){
                $result = $db->query($sql);                
                while($resarr = mysqli_fetch_array($result,MYSQLI_BOTH)){
                    $row['id'][$i] = $resarr['id'];
                    $row['username'][$i] = $resarr['username'];
                    $row['email'][$i] = $resarr['email'];
                    $row['password'][$i] = $resarr['password'];                    
                    $row['isactive'][$i] = $resarr['isactive'];
                    $row['ipaddress'][$i] = $resarr['ipaddress'];
                    $row['publishdate'][$i] = $resarr['publishdate']; 
                    $i++;
                }
                $row['count'] = $i;
            }else{
                echo 'Not Connected<br/>';
            }
            
            return ($row);
        }
        
        public function getUserAddresses($uid=NULL, $id=NULL, $state=NULL, $city=NULL, $pincode=NULL, $isprimary=NULL, $isactive=NULL){
            $condition = "";
            if(!empty($uid)){
                $condition.=empty($condition)?" uid='$uid' ":" AND uid='$uid' ";              
            }
            if(!empty($id)){
                $condition.=empty($condition)?" id='$id' ":" AND id='$id' ";              
            }
            if(!empty($state)){
                $condition.=empty($condition)?" state='$state' ":" AND state='$state' ";              
            }
            if(!empty($city)){
                $condition.=empty($condition)?" city='$city' ":" AND city='$city' ";              
            }
            if(!empty($pincode)){
                $condition.=empty($condition)?" pincode='$pincode' ":" AND pincode='$pincode' ";              
            }
            if(!empty($isprimary)){
                $condition.=empty($condition)?" isprimary='$isprimary' ":" AND isprimary='$isprimary' ";              
            }
            
            if(!empty($isactive)){
                $condition.=empty($condition)?" isactive='$isactive' ":" AND isactive='$isactive' ";              
            }
            
            $condition = trim(str_replace('  ', ' ', $condition));            
            
            $sql = (!empty($condition))?
                    "SELECT * FROM `user_address` WHERE $condition":
                    "SELECT * FROM `user_address`";
            //echo $sql;
            $db = new db();
            $db->connect();
            $dbname = MAINDB;
            $db->select_db($dbname);
            
            $i=0; $row = Array();
            if($db->is_connected()==true){
                $result = $db->query($sql);                
                while($resarr = mysqli_fetch_array($result,MYSQLI_BOTH)){
                    $row['id'][$i] = $resarr['id'];
                    $row['uid'][$i] = $resarr['uid'];                    
                    $row['address_line'][$i] = $resarr['address_line'];
                    $row['city'][$i] = $resarr['city'];
                    $row['state'][$i] = $resarr['state'];
                    $row['country'][$i] = $resarr['country'];
                    $row['pincode'][$i] = $resarr['pincode'];
                    $row['landmark'][$i] = $resarr['landmark'];
                    $row['contact_person'][$i] = $resarr['contact_person'];
                    $row['contact_num'][$i] = $resarr['contact_num'];
                    $row['isprimary'][$i] = $resarr['isprimary'];
                    $row['isactive'][$i] = $resarr['isactive'];
                    $row['ipaddress'][$i] = $resarr['ipaddress'];
                    $row['publishdate'][$i] = $resarr['publishdate']; 
                    $i++;
                }
                $row['count'] = $i;
            }else{
                echo 'Not Connected<br/>';
            }
            
            return ($row);
        }
        
        public function getTempCart($uid=NULL){
            $condition = "";
            if(!empty($uid)){
                $condition.=empty($condition)?" uid='$uid' ":" AND uid='$uid' ";              
            }
            
            $condition = trim(str_replace('  ', ' ', $condition));            
            
            $query = (!empty($condition))?
                    "SELECT * FROM `temp_cart` WHERE $condition ORDER BY id":
                    "SELECT * FROM `temp_cart` ORDER BY id";       
            
            //$query = "SELECT * FROM `deliveryslots` ORDER BY id";            
            
            $db = new db();
            $db->connect();
            $dbname = MAINDB;
            $db->select_db($dbname);
            //echo $query;
            $i=0; $row = Array();
            if($db->is_connected()==true){
                $result = $db->query($query);                
                while($resarr = mysqli_fetch_array($result,MYSQLI_BOTH)){
                    $row['id'][$i] = $resarr['id'];
                    $row['uid'][$i] = $resarr['uid'];
                    $row['cart_items'][$i] = $resarr['cart_items'];
                    $row['cart_amount'][$i] = $resarr['cart_amount'];
                    $row['shipping_charges'][$i] = $resarr['shipping_charges'];
                    $row['is_ordered'][$i] = $resarr['is_ordered'];
                    $row['publishdate'][$i] = $resarr['publishdate'];
                    
                    $i++;
                }
                $row['count'] = $i;
            }else{
                echo 'Not Connected<br/>';
            }
            
            return ($row);
        }
        
        public function getWalletBalance($uid=NULL){
            $condition = "";
            if(!empty($uid)){
                $condition.=empty($condition)?" uid='$uid' ":" AND uid='$uid' ";              
            }
            
            $condition = trim(str_replace('  ', ' ', $condition));            
            
            $query = (!empty($condition))?
                    "SELECT * FROM `wallet` WHERE $condition ORDER BY publishdate DESC LIMIT 1":
                    "SELECT * FROM `wallet` ORDER BY id";       
            
            $db = new db();
            $db->connect();
            $dbname = MAINDB;
            $db->select_db($dbname);
            
            $i=0; $row = Array();
            if($db->is_connected()==true){
                $result = $db->query($query);                
                while($resarr = mysqli_fetch_array($result,MYSQLI_BOTH)){
                    $row['id'][$i] = $resarr['id'];
                    $row['uid'][$i] = $resarr['uid'];
                    $row['credit'][$i] = $resarr['credit'];
                    $row['creditnote'][$i] = $resarr['creditnote'];
                    $row['debit'][$i] = $resarr['debit'];
                    $row['debitnote'][$i] = $resarr['debitnote'];
                    $row['balance'][$i] = $resarr['balance'];
                    $row['isactive'][$i] = $resarr['isactive'];
                    $row['ipaddress'][$i] = $resarr['ipaddress'];
                    $row['publishdate'][$i] = $resarr['publishdate'];
                    
                    $i++;
                }
                $row['count'] = $i;
            }else{
                echo 'Not Connected<br/>';
            }
            
            return ($row);
        }
        
        public function getWalletDetails($uid=NULL){
            $condition = "";
            if(!empty($uid)){
                $condition.=empty($condition)?" uid='$uid' ":" AND uid='$uid' ";              
            }
            
            $condition = trim(str_replace('  ', ' ', $condition));            
            
            $query = (!empty($condition))?
                    "SELECT * FROM `wallet` WHERE $condition ORDER BY publishdate DESC":
                    "SELECT * FROM `wallet` ORDER BY id";       
            
            $db = new db();
            $db->connect();
            $dbname = MAINDB;
            $db->select_db($dbname);
            
            $i=0; $row = Array();
            if($db->is_connected()==true){
                $result = $db->query($query);                
                while($resarr = mysqli_fetch_array($result,MYSQLI_BOTH)){
                    $row['id'][$i] = $resarr['id'];
                    $row['uid'][$i] = $resarr['uid'];
                    $row['credit'][$i] = $resarr['credit'];
                    $row['creditnote'][$i] = $resarr['creditnote'];
                    $row['debit'][$i] = $resarr['debit'];
                    $row['debitnote'][$i] = $resarr['debitnote'];
                    $row['balance'][$i] = $resarr['balance'];
                    $row['isactive'][$i] = $resarr['isactive'];
                    $row['ipaddress'][$i] = $resarr['ipaddress'];
                    $row['publishdate'][$i] = $resarr['publishdate'];
                    
                    $i++;
                }
                $row['count'] = $i;
            }else{
                echo 'Not Connected<br/>';
            }
            
            return ($row);
        }
        
        public function getSubscriptionPacks($id=NULL) {
            $condition = "";
            if(!empty($id)){
                $condition.=empty($condition)?" id='$id' ":" AND id='$id' ";              
            }
            
            $condition = trim(str_replace('  ', ' ', $condition));            
            
            $query = (!empty($condition))?
                    "SELECT * FROM `subscription_packs` WHERE $condition AND isactive='1'":
                    "SELECT * FROM `subscription_packs` WHERE isactive='1'";       
            //echo $query; 
            $db = new db();
            $db->connect();
            $dbname = MAINDB;
            $db->select_db($dbname);
            
            $i=0; $row = Array();
            if($db->is_connected()==true){
                $result = $db->query($query);                
                while($resarr = mysqli_fetch_array($result,MYSQLI_BOTH)){
                    $row['id'][$i] = $resarr['id'];
                    $row['title'][$i] = $resarr['title'];
                    $row['description'][$i] = $resarr['description'];
                    $row['rate'][$i] = $resarr['rate'];                    
                    $row['isactive'][$i] = $resarr['isactive'];
                    $row['ipaddress'][$i] = $resarr['ipaddress'];
                    $row['publishdate'][$i] = $resarr['publishdate'];
                    
                    $i++;
                }
                $row['count'] = $i;
            }else{
                echo 'Not Connected<br/>';
            }
            
            return ($row);
        }
        /////////////////////////////////////////////////////////////////////////////////////////
        //END USER AND SELLER RELATED FUNCTIONS//////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////
        
        public function getCoupon($id=NULL,$code=NULL,$discount_type=NULL,$start_dt=NULL,$end_dt=NULL,$isactive=NULL){
            $condition = "";
            if(!empty($id)){
                $condition.=empty($condition)?" id='$id' ":" AND id='$id' ";              
            }
            if(!empty($code)){
                $condition.=empty($condition)?" code='$code' ":" AND code='$code' ";              
            }
            if(!empty($discount_type)){
                $condition.=empty($condition)?" discount_type='$discount_type' ":" AND discount_type='$discount_type' ";              
            }
            if(!empty($start_dt)){
                $condition.=empty($condition)?" start_date>='$start_dt' ":" AND start_date>='$start_dt' ";              
            }
            if(!empty($end_dt)){
                $condition.=empty($condition)?" end_date<='$end_dt' ":" AND end_date<='$end_dt' ";              
            }
            if(!empty($isactive)){
                $condition.=empty($condition)?" isactive='$isactive' ":" AND isactive='$isactive' ";
            }
            
            $condition = trim(str_replace('  ', ' ', $condition));            
            
            $query = (!empty($condition))?
                    "SELECT * FROM `coupons` WHERE $condition ORDER BY id":
                    "SELECT * FROM `coupons` ORDER BY id";            
            
            $db = new db();
            $db->connect();
            $dbname = MAINDB;
            $db->select_db($dbname);
            //echo $query;
            $i=0; $row = Array();
            if($db->is_connected()==true){
                $result = $db->query($query);                
                while($resarr = mysqli_fetch_array($result,MYSQLI_BOTH)){
                    $row['id'][$i] = $resarr['id'];
                    $row['code'][$i] = $resarr['code'];
                    $row['discount_type'][$i] = $resarr['discount_type'];
                    $row['discount'][$i] = $resarr['discount'];
                    $row['max_usage'][$i] = $resarr['max_usage'];
                    $row['start_date'][$i] = $resarr['start_date'];
                    $row['end_date'][$i] = $resarr['end_date'];
                    $row['isactive'][$i] = $resarr['isactive'];
                    $row['ipaddress'][$i] = $resarr['ipaddress'];
                    $row['publishdate'][$i] = $resarr['publishdate']; 
                    $i++;
                }
                $row['count'] = $i;
            }else{
                echo 'Not Connected<br/>';
            }
            
            return ($row);
        }

        public function getDeliverySlots($id=NULL){
            $condition = "";
            if(!empty($id)){
                $condition.=empty($condition)?" id='$id' ":" AND id='$id' ";              
            }
            
            $condition = trim(str_replace('  ', ' ', $condition));            
            
            $query = (!empty($condition))?
                    "SELECT * FROM `deliveryslots` WHERE $condition ORDER BY id":
                    "SELECT * FROM `deliveryslots` ORDER BY id";       
            
            //$query = "SELECT * FROM `deliveryslots` ORDER BY id";            
            
            $db = new db();
            $db->connect();
            $dbname = MAINDB;
            $db->select_db($dbname);
            //echo $query;
            $i=0; $row = Array();
            if($db->is_connected()==true){
                $result = $db->query($query);                
                while($resarr = mysqli_fetch_array($result,MYSQLI_BOTH)){
                    $row['id'][$i] = $resarr['id'];
                    $row['start_time'][$i] = $resarr['start_time'];
                    $row['end_time'][$i] = $resarr['end_time'];
                    $row['display'][$i] = $resarr['display'];
                    $row['isactive'][$i] = $resarr['isactive'];
                    $i++;
                }
                $row['count'] = $i;
            }else{
                echo 'Not Connected<br/>';
            }
            
            return ($row);
        }
        
        public function getOrders($userid=NULL,$orderid=NULL,$isactive=NULL,$status=NULL){
            $condition = "";
            if(!empty($userid)){
                $condition.=empty($condition)?" userid='$userid' ":" AND userid='$userid' ";              
            }
            if(!empty($orderid)){
                $condition.=empty($condition)?" orderid='$orderid' ":" AND orderid='$orderid' ";              
            }            
            if(!empty($isactive)){
                $condition.=empty($condition)?" isactive='$isactive' ":" AND isactive='$isactive' ";
            }
            if(!empty($status)){
                $condition.=empty($condition)?" status='$status' ":" AND status=$status ";
            }
            
            $condition = trim(str_replace('  ', ' ', $condition));            
            
            $query = (!empty($condition))?
                    "SELECT * FROM `orders` WHERE $condition ORDER BY orderdate DESC":
                    "SELECT * FROM `orders` ORDER BY orderdate DESC";            
            //echo $query;
            $db = new db();
            $db->connect();
            $dbname = MAINDB;
            $db->select_db($dbname);
            //echo $query;
            $i=0; $row = Array();
            if($db->is_connected()==true){
                $result = $db->query($query);                
                while($resarr = mysqli_fetch_array($result,MYSQLI_BOTH)){
                    $row['id'][$i] = $resarr['id'];
                    $row['orderid'][$i] = $resarr['orderid'];
                    $row['userid'][$i] = $resarr['userid'];
                    $row['items'][$i] = $resarr['items'];
                    $row['cartcount'][$i] = $resarr['cartcount'];
                    $row['orderamount'][$i] = $resarr['orderamount'];
                    $row['shippingcharges'][$i] = $resarr['shippingcharges'];
                    $row['xiocashused'][$i] = $resarr['xiocashused'];
                    $row['couponcode'][$i] = $resarr['couponcode'];
                    $row['discount'][$i] = $resarr['discount']; 
                    $row['payableamount'][$i] = $resarr['payableamount']; 
                    $row['shippingaddress'][$i] = $resarr['shippingaddress']; 
                    $row['orderdate'][$i] = $resarr['orderdate']; 
                    $row['deliverydate'][$i] = $resarr['deliverydate']; 
                    $row['deleveryslot'][$i] = $resarr['deleveryslot']; 
                    $row['paymentmode'][$i] = $resarr['paymentmode']; 
                    $row['ipaddress'][$i] = $resarr['ipaddress']; 
                    $row['isactive'][$i] = $resarr['isactive'];
                    $row['status'][$i] = $resarr['status'];
                    $i++;
                }
                $row['count'] = $i;
            }else{
                echo 'Not Connected<br/>';
            }
            
            return ($row);
        }

        /////////////////////////////////////////////////////////////////////////////////////////
        //STATIC DATABASE FUNCTIONS//////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////
        
        public function getcategory($pid=0,$id=NULL,$slug=NULL,$isactive=NULL){
            $condition = "";
            if(!empty($pid) OR ($pid==0)){
                if($slug==NULL){
                    $condition.=empty($condition)?" pid='$pid' ":" AND pid='$pid' ";              
                }
            }
            if(!empty($id)){
                $condition.=empty($condition)?" id='$id' ":" AND id='$id' ";              
            }
            if(!empty($slug)){
                $condition.=empty($condition)?" slug='$slug' ":" AND slug='$slug' ";              
            }
            if(!empty($isactive)){
                $condition.=empty($condition)?" isactive='$isactive' ":" AND isactive='$isactive' ";
            }
            
            $condition = trim(str_replace('  ', ' ', $condition));            
            
            $query = (!empty($condition))?
                    "SELECT * FROM `category` WHERE $condition ORDER BY weight":
                    "SELECT * FROM `category` ORDER BY weight";            
            
            $db = new db();
            $db->connect();
            $dbname = MAINDB;
            $db->select_db($dbname);
            //echo $query;
            $i=0; $row = Array();
            if($db->is_connected()==true){
                $result = $db->query($query);                
                while($resarr = mysqli_fetch_array($result,MYSQLI_BOTH)){
                    $row['id'][$i] = $resarr['id'];
                    $row['pid'][$i] = $resarr['pid'];
                    $row['name'][$i] = $resarr['name'];
                    $row['slug'][$i] = $resarr['slug'];
                    $row['description'][$i] = $resarr['description'];
                    $row['image'][$i] = $resarr['image'];
                    $row['isactive'][$i] = $resarr['isactive'];
                    $row['isquicklink'][$i] = $resarr['isquicklink'];
                    $row['weight'][$i] = $resarr['weight'];
                    $row['ipaddress'][$i] = $resarr['ipaddress'];
                    $row['publishdate'][$i] = $resarr['publishdate']; 
                    $i++;
                }
                $row['count'] = $i;
            }else{
                echo 'Not Connected<br/>';
            }
            
            return ($row);
        }
        
        public function getCat($id=NULL,$slug=NULL,$isactive=NULL){
            $condition = "";
            
            if(!empty($id)){
                $condition.=empty($condition)?" id='$id' ":" AND id='$id' ";              
            }
            if(!empty($slug)){
                $condition.=empty($condition)?" slug='$slug' ":" AND slug='$slug' ";              
            }
            if(!empty($isactive)){
                $condition.=empty($condition)?" isactive='$isactive' ":" AND isactive='$isactive' ";
            }
            
            $condition = trim(str_replace('  ', ' ', $condition));            
            
            $query = (!empty($condition))?
                    "SELECT * FROM `category` WHERE $condition ORDER BY weight":
                    "SELECT * FROM `category` ORDER BY weight";            
            
            $db = new db();
            $db->connect();
            $dbname = MAINDB;
            $db->select_db($dbname);
            //echo $query;
            $i=0; $row = Array();
            if($db->is_connected()==true){
                $result = $db->query($query);                
                while($resarr = mysqli_fetch_array($result,MYSQLI_BOTH)){
                    $row['id'][$i] = $resarr['id'];
                    $row['pid'][$i] = $resarr['pid'];
                    $row['name'][$i] = $resarr['name'];
                    $row['slug'][$i] = $resarr['slug'];
                    $row['description'][$i] = $resarr['description'];
                    $row['isactive'][$i] = $resarr['isactive'];
                    $row['isquicklink'][$i] = $resarr['isquicklink'];
                    $row['weight'][$i] = $resarr['weight'];
                    $row['ipaddress'][$i] = $resarr['ipaddress'];
                    $row['publishdate'][$i] = $resarr['publishdate']; 
                    $i++;
                }
                $row['count'] = $i;
            }else{
                echo 'Not Connected<br/>';
            }
            
            return ($row);
        }
        
        public function getquicklinks($isactive=NULL){
            $condition = "";
            if(!empty($isactive)){
                $condition.=empty($condition)?" isactive='$isactive' ":" AND isactive='$isactive' ";
            }
            
            $condition = trim(str_replace('  ', ' ', $condition)); 
            
            $query = (!empty($condition))?
                    "SELECT * FROM `category` WHERE isquicklink='1' AND $condition ORDER BY weight":
                    "SELECT * FROM `category` WHERE isquicklink='1' ORDER BY weight";            
            
            $db = new db();
            $db->connect();
            $dbname = MAINDB;
            $db->select_db($dbname);
            
            $i=0; $row = Array();
            if($db->is_connected()==true){
                $result = $db->query($query);                
                while($resarr = mysqli_fetch_array($result,MYSQLI_BOTH)){
                    $row['id'][$i] = $resarr['id'];
                    $row['pid'][$i] = $resarr['pid'];
                    $row['name'][$i] = $resarr['name'];
                    $row['slug'][$i] = $resarr['slug'];
                    $row['isactive'][$i] = $resarr['isactive'];
                    $row['isquicklink'][$i] = $resarr['isquicklink'];
                    $row['weight'][$i] = $resarr['weight'];
                    $row['ipaddress'][$i] = $resarr['ipaddress'];
                    $row['publishdate'][$i] = $resarr['publishdate']; 
                    $i++;
                }
                $row['count'] = $i;
            }else{
                echo 'Not Connected<br/>';
            }
            
            return ($row);
        }   
        
        public function getcountry($country_id=NULL, $country_name=NULL, $country_code=NULL, $International_Zone_ID=NULL, $isactive=NULL){
            $condition = "";
            if(!empty($country_id)){
                $condition.=empty($condition)?" country_id='$country_id' ":" AND country_id='$country_id' ";              
            }
            if(!empty($country_name)){
                $condition.=empty($condition)?" country_name='$country_name' ":" AND country_name='$country_name' ";              
            }
            if(!empty($country_code)){
                $condition.=empty($condition)?" country_code='$country_code' ":" AND country_code='$country_code' ";              
            }
            if(!empty($International_Zone_ID)){
                $condition.=empty($condition)?" International_Zone_ID='$International_Zone_ID' ":" AND International_Zone_ID='$International_Zone_ID' ";              
            }
            if(!empty($isactive)){
                $condition.=empty($condition)?" isactive='$isactive' ":" AND isactive='$isactive' ";              
            }
            
            $condition = trim(str_replace('  ', ' ', $condition));            
            
            $query = (!empty($condition))?
                    "SELECT * FROM `tbl_country` WHERE $condition":
                    "SELECT * FROM `tbl_country`";
            
            $db = new db();
            $db->connect();
            $dbname = MAINDB;
            $db->select_db($dbname);
            
            $i=0; $row = Array();
            if($db->is_connected()==true){
                $result = $db->query($query);                
                while($resarr = mysqli_fetch_array($result,MYSQLI_BOTH)){
                    $row['country_id'][$i] = $resarr['country_id'];
                    $row['country_name'][$i] = $resarr['country_name'];
                    $row['country_code'][$i] = $resarr['country_code'];
                    $row['International_Zone_ID'][$i] = $resarr['International_Zone_ID'];
                    $row['publishdate'][$i] = $resarr['publishdate'];
                    $row['isactive'][$i] = $resarr['isactive'];
                    $row['weight'][$i] = $resarr['weight'];
                    $i++;
                }
                $row['count'] = $i;
            }else{
                echo 'Not Connected<br/>';
            }
            
            return ($row);
        }
        
        public function getstate($country_id=NULL, $state_id=NULL, $isactive=NULL) {
            $condition = "";
            if(!empty($country_id)){
                $condition.=empty($condition)?" country_id='$country_id' ":" AND country_id='$country_id' ";              
            }
            if(!empty($state_id)){
                $condition.=empty($condition)?" state_id='$state_id' ":" AND state_id='$state_id' ";              
            }
            if(!empty($isactive)){
                $condition.=empty($condition)?" isactive='$isactive' ":" AND isactive='$isactive' ";              
            }
            
            $condition = trim(str_replace('  ', ' ', $condition));            
            
            $query = (!empty($condition))?
                    "SELECT * FROM `tbl_state` WHERE $condition":
                    "SELECT * FROM `tbl_state`";
            
            $db = new db();
            $db->connect();
            $dbname = MAINDB;
            $db->select_db($dbname);
            
            $i=0; $row = Array();
            if($db->is_connected()==true){
                $result = $db->query($query);                
                while($resarr = mysqli_fetch_array($result,MYSQLI_BOTH)){
                    $row['state_id'][$i] = $resarr['state_id'];
                    $row['country_id'][$i] = $resarr['country_id'];
                    $row['state_name'][$i] = $resarr['state_name'];
                    $row['description'][$i] = $resarr['description'];
                    $row['publishdate'][$i] = $resarr['publishdate'];
                    $row['isactive'][$i] = $resarr['isactive'];
                    $row['weight'][$i] = $resarr['weight'];
                    $i++;
                }
                $row['count'] = $i;
            }else{
                echo 'Not Connected<br/>';
            }
            
            return ($row);
        }  
        
        
        
        
        /////////////////////////////////////////////////////////////////////////////////////////
        //END STATIC DATABASE FUNCTIONS//////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////
        
        
        
        /////////////////////////////////////////////////////////////////////////////////////////
        //PRODUCT RELATED FUNCTIONS//////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////
        
        public function getProducts($pid=NULL, $sku=NULL, $category=NULL, $isactive=NULL, $order=NULL, $isoffer=NULL) {
            $condition = "";
            if(!empty($pid)){
                $condition.=empty($condition)?" pid='$pid' ":" AND pid='$pid' ";
            }  
            if(!empty($sku)){
                $condition.=empty($condition)?" sku='$sku' ":" AND sku='$sku' ";
            }
            if(!empty($category)){
                $condition.=empty($condition)?" category='$category' ":" AND category='$category' ";
            }           
            if(!empty($isactive)){
                $condition.=empty($condition)?" isactive='$isactive' ":" AND isactive='$isactive' ";
            }
            
            if(!empty($isoffer)){
                $condition.=empty($condition)?" isoffer='$isoffer' ":" AND isoffer='$isoffer' ";
            }
            $condition = trim(str_replace('  ', ' ', $condition));            
            
            $query = (!empty($condition))?
                    "SELECT * FROM `product` WHERE $condition ".((!empty($order))?"ORDER BY $order":""):
                    "SELECT * FROM `product` ".((!empty($order))?"ORDER BY $order":"");            
            //echo $query;
            $db = new db();
            $db->connect();
            $dbname = MAINDB;
            $db->select_db($dbname);
            
            $i=0; $row = Array();
            if($db->is_connected()==true){
                $result = $db->query($query);                
                while($resarr = mysqli_fetch_array($result,MYSQLI_BOTH)){
                    $row['id'][$i] = $resarr['id'];
                    $row['pid'][$i] = $resarr['pid'];
                    $row['sku'][$i] = $resarr['sku'];
                    $row['category'][$i] = $resarr['category'];                   
                    $row['title'][$i] = $resarr['title'];
                    $row['title_hin'][$i] = $resarr['title_hin'];
                    $row['tags'][$i] = $resarr['tags'];
                    $row['description'][$i] = $resarr['description']; 
                    $row['product_weight'][$i] = $resarr['product_weight'];                     
                    $row['mrp'][$i] = $resarr['mrp'];
                    $row['selling_price'][$i] = $resarr['selling_price'];                    
                    $row['image'][$i] = $resarr['image'];
                    $row['isactive'][$i] = $resarr['isactive'];
                    $row['isoffer'][$i] = $resarr['isoffer'];
                    $row['offer_weight'][$i] = $resarr['offer_weight'];
                    $row['ipaddress'][$i] = $resarr['ipaddress'];
                    $row['publishdate'][$i] = $resarr['publishdate'];
                    $i++;
                }
                $row['count'] = $i;
            }else{
                echo 'Not Connected<br/>';
            }
            
            return ($row);
        }
        
        /////////////////////////////////////////////////////////////////////////////////////////
        //END PRODUCT RELATED FUNCTIONS//////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////////////////
    }
?>