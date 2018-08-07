<?php 
    if(isset($_POST['subscribephone'])){
        
            $lang       = $_POST['languagesw'];
            $username 	= "ahmad.kayed@ak.com.sa";
	    $password 	= "IGhufItAfmakGe5&";
	    $token  	= "Basic " . base64_encode($username . ':' . $password);
	    $phone  	= $_POST["subscribephone"];
	    $phone  	= preg_replace('/[() .+-]/', '', $phone);
        
      
          

	    if (strlen($phone) < 11 && substr($phone,0,1)=="0") $phone = substr($phone,1);

		$xml   =simplexml_load_file("phones.xml") or error_log(print_r('Error: Cannot add your subscribe',true));
	  	$code  = '';
	  	if ((substr($phone,0,4)=="00966"&&(strlen($phone)==14)||strlen($phone)==16)  || (substr($phone,0,2)=="966"&&(strlen($phone)==12)||strlen($phone)==14) ) {
	  		$phone = $phone;
	  	}elseif(strlen($phone)==9 || strlen($phone)==10){
	  		$phone = '00966'.$phone;
	  	}else{
	  		//die(header("HTTP/1.0 404 Not Found"));
                                   if($lang == 'ar'){
                header("Location: ./landing.html?value=2");
            } else {
              header("Location: ./landing_en.html?value=2");
            }
	  	}
              
		foreach($xml->children() as $promophone) {
			if ($promophone->number==''&&$phone!=''&&$promophone->number!=$phone) {
				$code = $promophone->code;
				$promophone->number = $phone;
                                if(isset($_POST['subscribeemail'])){
				$promophone->email = $_POST['subscribeemail'];
                                }
				$xml->asXML('phones.xml');
				break;
			}elseif ($promophone->number==$phone) {
				$phone = '';
                                                if($lang == 'ar'){
                                                    header("Location: ./landing.html?value=3");
                                                } else {
                                                  header("Location: ./landing_en.html?value=3");
                                                }
				echo($lang == 'ar' ? 'إن هذا الرقم مسجل' : 'Your phone number is already registered');
				break;
			}
		}


		if ($phone == '') {
		}elseif (strlen($phone)<10 || strlen($phone)>14) {
			//die(header("HTTP/1.0 404 Not Found"));
                           if($lang == 'ar'){
                header("Location: ./landing.html?value=2");
            } else {
              header("Location: ./landing_en.html?value=2");
            }
		echo ($lang == 'ar' ? 'إن هذا الرقم غير صحيح' : 'Please enter a valid phone number');
	    }else{
	        //error_log(print_r($phone, TRUE)); 
	        if ($lang == 'ar' ) {
	        	$mess = "شكراً لتسجيلك في موقعنا  theblueage.com! يمكنك استخدام الكود التالي: $code عند تسوّقك المقبل في أي من فروع بلوايج لتستمتع بخصم 20% على تشكيلتنا الجديدة! أهلاً وسهلاً بك";
	        }elseif ($lang == 'en' ) {
	        	$mess = "Thank you for your registration in our website theblueage.com! you can use the following promo code: $code when you shop at Blueage branches to enjoy 20% off on our new collection! ";
	        }
      $message   = array(
                  "from" 	=> 'Blueage',
                  "to"   	=> $phone,
                  "text" 	=> $mess
              );
       
            $data = json_encode($message);
            $ch   = curl_init('https://api.infobip.com/sms/1/text/single');
            curl_setopt($ch, CURLOPT_POST, true);
            //curl_setopt($ch, CURLOPT_USERPWD,$user);    
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Authorization: ' . $token
            ));    
            $result = curl_exec($ch);
            if(curl_errno($ch)) {
                $response = curl_error($ch);
                error_log(print_r($response, TRUE));
            } else {
                $response = $result;
                error_log(print_r($response, TRUE));
            }
            curl_close($ch);
            if($lang == 'ar'){
                header("Location: ./msg.html?value=1");
            } else {
              header("Location: ./msg_en.html?value=1");
            }
		   
die();

			echo ($lang == 'ar' ? 'شكرا لمشاركتك' : 'Thank you for subscribing!');
		}
	}else{
                if($lang == 'ar'){
                header("Location: ./landing.html?value=4");
            } else {
              header("Location: ./landing_en.html?value=4");
            }
		echo ('This is not a valid request');
	}
	

