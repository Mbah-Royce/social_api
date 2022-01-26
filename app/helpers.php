<?php

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
function imageUpload($path, $file)
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

?>