<?php

use App\Mail\AppMail;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

/**
 * Method to send api response
 * 
 * @param $data
 * @param $message
 * @param $statusCdoe
 * @return array 
 */
function httpResponse($data, $message = '', $statusCode = 200)
{
    $message = (is_array($message)) ? reset($message) : $message;
    $response['data'] = ($data) ?? [];
    $response['message'] = (is_array($message)) ? $message[0] : $message;
    return response()->json($response,$statusCode);
}

/**
 * Method to upload media
 * 
 * @param $path
 * @param $file
 * @return string
 */
function fileUpload($path, $file)
{
    if (!Storage::exists($path)) {
        Storage::makeDirectory($path);
    }
    $path = Storage::putFile($path, $file);
    return '/storage' . substr($path, 6);
}

/**
 * Method to return absolute media path
 * 
 * @param $path
 * @return string
 */
function getMediaUrl($path)
{
    return env('APP_URL') . $path;
}

/**
 * Method to send email
 * 
 * @param $reciever
 * @param $template
 * @param $subject
 * @param $data
 * @return string
 */
function emailNotification($reciever,$template,$subject,$data,$queue = 'studentCreation')
{
    Mail::to($reciever)->send((new AppMail($template,$subject,$data))->onQueue($queue));
    return (Mail::failures());
}

/**
 * Method to extract info from CSV file
 * 
 * @param $filename
 * @param $delimiter
 * @return array
 */
function importCSV($filename, $delimiter = ','){
    if(!file_exists(public_path($filename)) || !is_readable(public_path($filename))){
    return false;
    }
    $header = null;
    $data = array();
    if (($handle = fopen(public_path($filename), 'r')) !== false){
      while (($row = fgetcsv($handle, 1000, $delimiter)) !== false){
        if(!$header)
          $header = $row;
            else
          $data[] = array_combine($header, $row);
      }
      fclose($handle);
    }
    return $data;
  }

  /**
 * Method to generate random string
 * 
 * @param $length
 * @return array
 */
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

/** 
* Method to generate random numbers
* 
* @param $length
* @return array
*/
function generateRandomNum($length = 5) {
   $characters = '0123456789';
   $charactersLength = strlen($characters);
   $randomString = '';
   for ($i = 0; $i < $length; $i++) {
       $randomString .= $characters[rand(0, $charactersLength - 1)];
   }
   return $randomString;
}
?>