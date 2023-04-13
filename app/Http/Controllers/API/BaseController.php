<?php


namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;


class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($status = true, $message, $result = [], $code = 200, $cookie = [])
    {
        $response = [
            'success' => $status,
            'message' => $message,
            'data'    => $result,
        ];
        if (empty($cookie)) {
            return response()->json($response, $code);
        } else {
            return response()->json($response, $code)->withCookie(cookie($cookie['name'], $cookie['value'], $cookie['minutes'], null, null, false, true));
        }
    }
}
