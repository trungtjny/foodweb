<?php

if (!function_exists('responseSuccess')) {
    function responseSuccess($data, $message, $statuscode)
    {
        return response()->json(['data' => $data, 'message' => $message], $statuscode);
    }
}

if (!function_exists('responseError')) {
    function responseError($data, $message, $statuscode)
    {
        return response()->json(['data' => $data, 'message' => $message], $statuscode);
    }
}
?>