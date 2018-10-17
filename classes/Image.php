<?php
class Image {

		public static function uploadImage($formname, $query, $params) {
				// echo 'we are now here\n'
				// $image = file_get_contents($_FILES[$formname]['tmp_name']);
				// echo $image;
				//$img = imagecreatefromstring($image);
				//imagepng($img, './uploads/test.png');
				
				$image = base64_encode(file_get_contents($_FILES[$formname]['tmp_name']));

				$options = array('http'=>array(
					'method'=>"POST",
					'header'=>"Authorization: Bearer 9e5addc0d992dea6f0915cfeba92625b39a37900\n".
					"Content-Type: application/x-www.form-urlencoded",
					'content'=>$image
				));

				$context = stream_context_create($options);

				$imgurURL = "https://api.imgur.com/3/upload";

				if ($_FILES[$formname]['size'] > 10240000) {
					die('Image too big, must be 10MB or less');
				}

				$response = file_get_contents($imgurURL, false, $context);
				$response = json_decode($response);

				$preparams = array($formname=>$response->data->link);

				$params = $preparams + $params;

				DB::query($query, $params);
				
		}

}
?>