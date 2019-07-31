<?php

namespace App\Models;

 /**
  * @version 1.0.0
  */
class Response
{
    /**
     * Construct method to return response.
     *
     * @since 1.0.0
     *
     * @param string $status
     * @param mixed $data
     * @param int $code
     *
     * @return \Illuminate\Http\Response
     */
    public static function generate(string $status, $data, int $code)
    {
        return response()->json([
            'status' => $status,
            'data' => $data,
            'code' => $code,
        ], $code);
    }
}
