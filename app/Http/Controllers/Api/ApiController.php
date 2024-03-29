<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\ApiValidationFailedException;

abstract class ApiController extends Controller
{
    const STATUS_ERROR = 'error';
    const STATUS_SUCCESS = 'success';

    const CODE_SUCCESS = 200;
    const CODE_ERROR = 400;
    const CODE_NOT_FOUND = 404;
    const CODE_UNAUTHORIZED = 401;

    public function validateWithException(Request $request, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = Validator::make($request->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            throw new ApiValidationFailedException($validator);
        }
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     *
     * @return ApiController
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @param string $message
     *
     * @return mixed
     */
    protected function responseNotFoundWithMessage($message = 'Not found')
    {
        return $this->setStatusCode(self::CODE_NOT_FOUND)
            ->response(
                [
                'status' => self::STATUS_ERROR,
                'message' => $message,
                ]
            );
    }

    /**
     * @return mixed
     */
    protected function responseUnauthorized()
    {
        return $this->setStatusCode(self::CODE_UNAUTHORIZED)
            ->response(
                [
                    'status' => self::STATUS_ERROR,
                    'message' => 'Unauthorized',
                ]
            );
    }

    /**
     * @param array $data
     * @param array $headers
     *
     * @return mixed
     */
    public function response(array $data, $headers = [])
    {
        return Response::json($data, $this->getStatusCode(), $headers);
    }

    /**
     * @param $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseSuccessWithMessage($message = 'Success')
    {
        return $this->setStatusCode(self::CODE_SUCCESS)
            ->response(
                [
                        'status' => self::STATUS_SUCCESS,
                        'message' => $message,
                        ]
            );
    }

    /**
     * @param $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function responseErrorWithMessage($message = 'Error')
    {
        return $this->setStatusCode(self::CODE_ERROR)
            ->response(
                [
                    'status' => self::STATUS_ERROR,
                    'message' => $message,
                ]
            );
    }

    protected function responseSuccess()
    {
        return $this->setStatusCode(self::CODE_SUCCESS)
            ->response(
                [
                'status' => self::STATUS_SUCCESS,
                ]
            );
    }

    protected function responseSuccessWithExtrasAndMessage(array $extras = [], $message = null)
    {
        $result = [
            'status' => self::STATUS_SUCCESS,
        ];
        if (isset($message)) {
            $result['message'] = $message;
        }

        return $this->setStatusCode(self::CODE_SUCCESS)
            ->response($result + $extras);
    }
}
