<?php 

	/*Get Data From POST Http Request*/
	$datas = file_get_contents('php://input');
	/*Decode Json From LINE Data Body*/
	$deCode = json_decode($datas,true);

	file_put_contents('log.txt', file_get_contents('php://input') . PHP_EOL, FILE_APPEND);

	$replyToken = $deCode['events'][0]['replyToken'];

	$messages = ["ถามหน่อย"];
	$messages['replyToken'] = $replyToken;
	$messages['messages'][0] = getFormatTextMessage(
		{  
			"type": "flex",
			"altText": "this is a flex message",
			"contents": {
			  "type": "bubble",
			  "body": {
				"type": "box",
				"layout": "vertical",
				"contents": [
				  {
					"type": "text",
					"text": "hello"
				  },
				  {
					"type": "text",
					"text": "world"
				  }
				]
			  }
			}
		  }
	);

	$encodeJson = json_encode($messages);

	$LINEDatas['url'] = "https://api.line.me/v2/bot/message/reply";
  	$LINEDatas['token'] = "sBXNmiT/eVI9C2js6vSG5i9CVJbbsuWPBKDuaGlFF4n3CjCWtF5EvY/zQzdBLydfv6I2dr1eYjcUYmSJVKNj7QrUSrxpnbbR0MaKcYTN9choWxvLUJu60gvz/hbmh627jxTFDQNkVRUIlUoouN5PgQdB04t89/1O/w1cDnyilFU=";

  	$results = sentMessage($encodeJson,$LINEDatas);

	/*Return HTTP Request 200*/
	http_response_code(200);

	function getFormatTextMessage($text)
	{
		$datas = [];
		$datas['type'] = 'text';
		$datas['text'] = $text;

		return $datas;
	}

	function sentMessage($encodeJson,$datas)
	{
		$datasReturn = [];
		$curl = curl_init();
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $datas['url'],
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => $encodeJson,
		  CURLOPT_HTTPHEADER => array(
		    "authorization: Bearer ".$datas['token'],
		    "cache-control: no-cache",
		    "content-type: application/json; charset=UTF-8",
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		    $datasReturn['result'] = 'E';
		    $datasReturn['message'] = $err;
		} else {
		    if($response == "{}"){
			$datasReturn['result'] = 'S';
			$datasReturn['message'] = 'Success';
		    }else{
			$datasReturn['result'] = 'E';
			$datasReturn['message'] = $response;
		    }
		}

		return $datasReturn;
	}

	// private void handleTextContent(String replyToken, Event event, TextMessageContent content) {
    //     String text = content.getText();
    //     .....

    //     log.info("Got text message from %s : %s", replyToken, text);

    //     switch (text) {
    //         .....
    //         case "Flex Back": {
    //             String userId = event.getSource().getUserId();
    //             if(userId != null) {
    //                 lineMessagingClient.linkRichMenuIdToUser(userId, homeMenu);
    //                 return;
    //             }
    //             break;
    //         }
    //         case "Flex Restaurant": {
    //             this.reply(replyToken, new RestaurantFlexMessageSupplier().get());
    //             break;
    //         }
    //         case "Flex Menu": {
    //             this.reply(replyToken, new RestaurantMenuFlexMessageSupplier().get());
    //             break;
    //         }
    //         case "Flex Receipt": {
    //             this.reply(replyToken, new ReceiptFlexMessageSupplier().get());
    //             break;
    //         }
    //         case "Flex News": {
    //             this.reply(replyToken, new NewsFlexMessageSupplier().get());
    //             break;
    //         }
    //         case "Flex Ticket": {
    //             this.reply(replyToken, new TicketFlexMessageSupplier().get());
    //             break;
    //         }
    //         case "Flex Catalogue": {
    //             this.reply(replyToken, new CatalogueFlexMessageSupplier().get());
    //             break;
    //         }
    //         default:
    //             log.info("Return echo message %s : %s", replyToken, text);
    //             this.replyText(replyToken, text);
    //     }
    // }
?>

