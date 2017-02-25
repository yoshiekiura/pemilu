<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class CRabbit extends Controller
{
	
    /*function index(){
	  $url = '167.205.7.229';
	  $port = 5672;
	  $user = 'pemiluuser';
	  $password = 'pemiluuser';
	  $virtual_host = '/pemilu';
		
	  //koneksi ke server rabbitmq
      $connection = new AMQPStreamConnection($url, $port, $user, $password, $virtual_host);
      $channel = $connection->channel();
	  
	  //deklarasi antrian yang digunakan
	  $queue_name = 'hello';
      $channel->queue_declare($queue_name,  //queue name 
								false,  //passive: false
								false,  //durable: false, if true the queue will survive if server restarts
								false,  //exclusive: false // the queue can be accessed in other channels
								false   //auto_delete: false //the queue won't be deleted once the channel is closed.
								);

	  //publish pesan ke antrian
	  $nama = 'nabila';
      $msg = new AMQPMessage($nama);
      $channel->basic_publish($msg,     //message
								'',     //exchange name
								'hello' //routing key
							 );

	  echo "[x] Sent $nama \n";
	  
      //echo " [x] Sent 'nabila'\n";

      $channel->close();
      $connection->close();

    }*/
	
	function receive(){
		$url = '167.205.7.229';
        $port = 5672;
		$user = 'pemiluuser';
		$password = 'pemiluuser';
		$virtual_host = '/pemilu';
		  
		$connection = new AMQPStreamConnection($url, $port, $user, $password, $virtual_host);
		$channel = $connection->channel();

		/*deklarasi antrian yang digunakan*/
		$queue_name = 'hello';
		$channel->queue_declare($queue_name,  //queue name 
								false,  //passive: false
								false,  //durable: false, if true the queue will survive if server restarts
								false,  //exclusive: false // the queue can be accessed in other channels
								false   //auto_delete: false //the queue won't be deleted once the channel is closed.
								);
		/*end*/
		
		echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

		/*
		$callback = function($msg) {
		   echo " [x] Received ", $msg->body, "\n";
		};

		$channel->basic_consume('hello', '', false, false, false, false, $callback);
		
		
		while(count($channel->callbacks)) {
			$channel->wait();
		}
		*/
		
		/*consume pesan dari antrian*/
		$receive = $channel->basic_get($queue_name); 
		$channel->basic_ack($receive->delivery_info['delivery_tag']); //sent keterangan(acknowladgement) 
																	  //message sudah diterima
		
		echo " [x] Received ", $receive->body, "\n";
		/*end*/
		
		$channel->close();
        $connection->close();
	}
	
	
	
	function getFormView(){
			$nama = "Testing Laravel";
			return view("formsms");
			//return view("formsms", compact('nama'));
		}
		
	function postForm(){
	  $url = '167.205.7.229';
	  $port = 5672;
	  $user = 'pemiluuser';
	  $password = 'pemiluuser';
	  $virtual_host = '/pemilu';
		
	  //koneksi ke server rabbitmq
      $connection = new AMQPStreamConnection($url, $port, $user, $password, $virtual_host);
      $channel = $connection->channel();
	  
	  //deklarasi antrian yang digunakan
	  $queue_name = 'hello';
      $channel->queue_declare($queue_name,  //queue name 
								false,  //passive: false
								false,  //durable: false, if true the queue will survive if server restarts
								false,  //exclusive: false // the queue can be accessed in other channels
								false   //auto_delete: false //the queue won't be deleted once the channel is closed.
								);
								
		//sms
		$tps = Input::get('tps'); 
		$kd1 = Input::get('kd1');
		$kd2 = Input::get('kd2');
		$kd3 = Input::get('kd3');

	 //publish pesan ke antrian
	  $nama = $tps;
      $message = new AMQPMessage($nama);
      $channel->basic_publish($message,     //message
								'',     //exchange name
								'hello' //routing key
							 );

	  echo "[x] Sent $nama \n";
//-------------------------------------------------------------------------------------------------------------------------------------------------------	  
	  //sms
	  
	  	//$result = DB::insert('insert into outbox (DestinationNumber, TextDecoded, CreatorID) values (?, ?, ?)', [$noTujuan, $message, 'Gammu']);
		//$result = DB::insert('insert into outbox (nohp, message) values (?, ?)', [$noTujuan, $message]);

		//mysql_connect("localhost", "root", "");
		//mysql_select_db("test");

		$query = DB::insert('insert into tps (ID_DataTPS, Kan1, Kan2, Kan3) values (?, ?, ?, ?)', [$tps, $kd1, $kd2, $kd3]);
		//$query="INSERT INTO message (tps, message) VALUES ('$_POST[tps]','$_POST[message]')";

			if($query){
				$message = "sms berhasil";
			}else{
				$message = "sms gagal";
			}
			
			return view('hasilForm', compact('message')); 
			
      //echo " [x] Sent 'nabila'\n";
//----------------------------------------------------------------------------------------------------------------------------------------------------------


      $channel->close();
      $connection->close();

    }
	
	
	
	
	
	
	
	
	
}