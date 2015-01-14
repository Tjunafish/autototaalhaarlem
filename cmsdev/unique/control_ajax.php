<?
if($_POST['hash'] != sha1(md5($_SERVER['REMOTE_ADDR']."h4esecure")))
	exit;
	
require '../../php/config.php';

if($_POST['action'] == "accept"){
	
	if(!$_sql->exists("professionals",array("id"=>$_POST['id'])))
		exit;
		
	$info = $_sql->fetch("object","professionals","WHERE `id` = '".$_sql->escape($_POST['id'])."'");
		
	$pass = substr(md5(time()),0,8);
	
	$_sql->update("professionals",array("password"=>$pass),"WHERE `id` = '".$info->id."'");
	
	$_core->send_mail($info->email,$_core->text("accept subject"),EMAIL_HEADER.$_core->text("accept message").$pass.EMAIL_FOOTER);

}elseif($_POST['action'] == "ticket"){
	
	if(!$_sql->exists("event_signups",array("id"=>$_POST['id'])))
		exit;
	
	$order = $_sql->fetch("object","event_signups","WHERE `id` = '".$_sql->escape($_POST['id'])."'");

	$message = $_core->text('thanks for buying ticket').':<br/><br/>
			   <a href="'.DOC_ROOT.'ticket/'.$order->order_id.'/'.sha1(md5($order->order_id.number_format($order->total,2,'.','').$order->event_id."secure")).'/'.time().'">'.$_core->text("download ticket").'</a>';

	$_core->send_mail(TARGET_EMAIL,"Opnieuw verstuurde ticket - Heart 4 Earth",$message);
	$_core->send_mail($order->email,$_core->text("subject ticket confirmation"),$message);	
	
	echo TARGET_EMAIL;
	
}elseif($_POST['action'] == "invoice"){
		
	if(!$_sql->exists("invoice",array("id"=>$_POST['id'])))
		exit;
	
	$invoice = $_sql->fetch("object","invoice","WHERE `id` = '".$_sql->escape($_POST['id'])."'");
	$info    = $_sql->fetch("object","professionals","WHERE `id` = '".$invoice->prof_id."'");

	$message = EMAIL_HEADER.$_core->text("dear").' '.$info->name.',<br/>
				<br/>'.$_core->text("invoice message").'<br/><br/>
				<a href="'.DOC_ROOT.'factuur/'.$invoice->number.'/'.sha1($invoice->id."h4esecurecode").'/'.uniqid().'factuur.pdf">'.$_core->text("download invoice").'</a>'.EMAIL_FOOTER;
	
	$_core->send_mail($info->email,$_core->text('invoice subject'),$message);
	$_core->send_mail(TARGET_EMAIL,'Kopie factuur - '.$info->name,$message);	
	
	echo TARGET_EMAIL;
	
}
?>