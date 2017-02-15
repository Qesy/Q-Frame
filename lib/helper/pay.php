<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Name   : Collection
 * Date	  : 20120107 
 * Author : Qesy 
 * QQ	  : 762264
 * Mail   : 762264@qq.com
 *
 *(̅_̅_̅(̲̅(̅_̅_̅_̅_̅_̅_̅_̅()ڪے 
 *
*/ 
class pay {
	private static $s_instance;
	public static function get_instance(){
		if (!isset(self::$s_instance)){
			self::$s_instance = new self();
		}
		return self::$s_instance;
	}
	
	//-- 支付宝 --
	public function alipay($orderNum = 0, $price = 0.00, $receive_name = '', $receive_address = '', $receive_zip = '', $receive_phone = '', $receive_mobile = ''){
		require_once("lib/api/pay/alipay/alipay.config.php");
		require_once("lib/api/pay/alipay/alipay_submit.class.php");
			$out_trade_no = $orderNum;
			$logistics_fee = '0.00';
			$logistics_payment = 'SELLER_PAY';
			$body = $orderNum;
			$discount= 0;//-- 优惠 --
				
			$md5parameter = array(
					"service" => "create_partner_trade_by_buyer",
					"partner" => trim($alipay_config['partner']),
					"payment_type"	=> "1",
					"return_url"	=> ALIPAY_RETURN,
					"notify_url"    => ALIPAY_NOTIFY,
					"seller_email"	=> "rongshenjiuye@163.com",
					"out_trade_no"	=> $out_trade_no,
					"subject"	=> "订单编号:".$out_trade_no,
					"price"	=> $price,
					"quantity"	=> "1",
					"logistics_fee"	=> $logistics_fee,
					"logistics_type"	=> "EXPRESS",
					"logistics_payment"	=> $logistics_payment,
					"body"	=> $body,
					"receive_name"	=> $receive_name,
					//"receive_address"	=> $receive_address,
					"receive_zip"	=> $receive_zip,
					"receive_phone"	=> $receive_phone,
					"receive_mobile"	=> $receive_mobile,
					"discount"		=>  $discount,
					"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
			);
			$para_filter = paraFilter($md5parameter);
			$para_sort = argSort($para_filter);
			$prestr = createLinkstring($para_sort);
			$sign=md5($prestr.$alipay_config['key']);
		
			$parameter = array(
					"service" => "create_partner_trade_by_buyer",
					"partner" => trim($alipay_config['partner']),
					"payment_type"	=> "1",
					"sign_type"  => "MD5",
					"return_url"	=> ALIPAY_RETURN,
					//"notify_url"    => ALIPAY_NOTIFY,
					"seller_email"	=> "rongshenjiuye@163.com",
					"out_trade_no"	=> $out_trade_no,
					"subject"	=> "订单编号:".$out_trade_no,
					"price"	=> $price,
					"sign"  => $sign,
					"quantity"	=> "1",
					"logistics_fee"	=> $logistics_fee,
					"logistics_type"	=> "EXPRESS",
					"logistics_payment"	=> $logistics_payment,
					"body"	=> $body,
					"receive_name"	=> urlencode($receive_name),
					//"receive_address"	=> urlencode($receive_address),
					"receive_zip"	=> $receive_zip,
					"receive_phone"	=> $receive_phone,
					"receive_mobile"	=> $receive_mobile,
					"discount"		=>  $discount,
					"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
			);
			//建立请求
			$alipaySubmit = new AlipaySubmit($alipay_config);
			$html_text = $alipaySubmit->buildRequestForm($parameter,"post", "确认");
			echo $html_text;
	
	}
	
	//-- 块钱 --
	public function bill($orderId = '', $orderAmount = 0.00){
		$merchantAcctId = "1002238772301";
		$inputCharset = "1";
		$pageUrl = 'http://'.WEB_DOMAIN.''.url(array('payapi', 'succes', $orderId));
		$bgUrl = KQ_BG_RETURN;
		$version =  "v2.0";
		$language =  "1";
		$signType =  "1";
		$orderAmount = ($orderAmount*100);
		$orderTime =  date("YmdHis");
		$payType = "00";
		$redoFlag = "0";
		$kq_all_para=kq_ck_null($inputCharset,'inputCharset');
		$kq_all_para.=kq_ck_null($pageUrl,"pageUrl");
		$kq_all_para.=kq_ck_null($bgUrl,'bgUrl');
		$kq_all_para.=kq_ck_null($version,'version');
		$kq_all_para.=kq_ck_null($language,'language');
		$kq_all_para.=kq_ck_null($signType,'signType');
		$kq_all_para.=kq_ck_null($merchantAcctId,'merchantAcctId');
		$kq_all_para.=kq_ck_null($orderId,'orderId');
		$kq_all_para.=kq_ck_null($orderAmount,'orderAmount');
		$kq_all_para.=kq_ck_null($orderTime,'orderTime');
		$kq_all_para.=kq_ck_null($payType,'payType');
		$kq_all_para.=kq_ck_null($redoFlag,'redoFlag');
		$kq_all_para.=kq_ck_null('QIH395MIGWRT5I4I','key');
		$kq_all_para=substr($kq_all_para,0,strlen($kq_all_para)-1);//var_dump($kq_all_para);exit;
		$signMsg= strtoupper(md5($kq_all_para));	
		$data['inputCharset']=$inputCharset;
		$data['version']=$version;
		$data['language']=$language;
		$data['signType']=$signType;
		$data['merchantAcctId']=$merchantAcctId;
		$data['orderId']=$orderId;
		$data['orderTime']=$orderTime;
		$data['redoFlag']=$redoFlag;
		$data['payType']=$payType;
		$data['pageUrl']=$pageUrl;
		$data['bgUrl']=$bgUrl;
		$data['orderAmount']=$orderAmount;
		$data['signMsg'] = $signMsg;
		return $data;
	}
	
	//-- 网银 --
	public function chinabank($orderId = '', $orderAmount = 0){
		$data['v_mid'] = CHINABANK_ID;
		$data['v_url'] = CHINABANK_RETURN;
		$data['key']   = CHINABANK_KEY;
		$data['v_oid'] = $orderId;
		$data['v_amount'] = trim($orderAmount);
		$data['v_moneytype'] = "CNY";
		$text = $data['v_amount'].$data['v_moneytype'].$data['v_oid'].$data['v_mid'].$data['v_url'].$data['key'];
		$data['v_md5info'] = strtoupper(md5($text));
		return $data;
	}
	
	public function alitest($orderNum = 0, $price = 0.01, $receive_name = '', $receive_address = 'shanghai', $receive_zip = '201600', $receive_phone = '021', $receive_mobile = '15618323440'){
		require_once("lib/api/pay/alipay/alipay.config.php");
		require_once("lib/api/pay/alipay/alipay_submit.class.php");
			$out_trade_no = $orderNum;
			$logistics_fee = '0.00';
			$logistics_payment = 'SELLER_PAY';
			$body = $orderNum;
			$discount= 0;//-- 优惠 --
				
			$md5parameter = array(
					"service" => "create_partner_trade_by_buyer",
					"partner" => trim($alipay_config['partner']),
					"payment_type"	=> "1",
					"return_url"	=> ALIPAY_RETURN,
					"notify_url"    => ALIPAY_NOTIFY,
					"seller_email"	=> "rongshenjiuye@163.com",
					"out_trade_no"	=> $out_trade_no,
					"subject"	=> "订单编号:".$out_trade_no,
					"price"	=> $price,
					"quantity"	=> "1",
					"logistics_fee"	=> $logistics_fee,
					"logistics_type"	=> "EXPRESS",
					"logistics_payment"	=> $logistics_payment,
					"body"	=> $body,
					"receive_name"	=> urlencode($receive_name),
					"receive_address"	=> urlencode($receive_address),
					"receive_zip"	=> $receive_zip,
					"receive_phone"	=> $receive_phone,
					"receive_mobile"	=> $receive_mobile,
					"discount"		=>  $discount,
					"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
			);
			$para_filter = paraFilter($md5parameter);
			$para_sort = argSort($para_filter);
			$prestr = createLinkstring($para_sort);
			$sign=md5($prestr.$alipay_config['key']);
		
			$parameter = array(
					"service" => "create_partner_trade_by_buyer",
					"partner" => trim($alipay_config['partner']),
					"payment_type"	=> "1",
					"sign_type"  => "MD5",
					"return_url"	=> ALIPAY_RETURN,
					//"notify_url"    => ALIPAY_NOTIFY,
					"seller_email"	=> "rongshenjiuye@163.com",
					"out_trade_no"	=> $out_trade_no,
					"subject"	=> "订单编号:".$out_trade_no,
					"price"	=> $price,
					"sign"  => $sign,
					"quantity"	=> "1",
					"logistics_fee"	=> $logistics_fee,
					"logistics_type"	=> "EXPRESS",
					"logistics_payment"	=> $logistics_payment,
					"body"	=> $body,
					"receive_name"	=> urlencode($receive_name),
					"receive_address"	=> urlencode($receive_address),
					"receive_zip"	=> $receive_zip,
					"receive_phone"	=> $receive_phone,
					"receive_mobile"	=> $receive_mobile,
					"discount"		=>  $discount,
					"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
			);
			//建立请求
			$alipaySubmit = new AlipaySubmit($alipay_config);
			$html_text = $alipaySubmit->buildRequestForm($parameter,"post", "确认");
			echo $html_text;
	
	}
}