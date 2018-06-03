<?php

namespace ApiBundle\Controller;

trait ResponseFormatterTrait
{
    /**
     * @param array $data
     * @return array
     */
    public function createResponse(array $data)
    {
        return $data;
    }

    /**
     * @param null $data
     * @param null $extra
     * @return array
     */
    public function createSuccess($data = null, $extra = null)
    {
        $response = ['success' => true];

        if ($data !== null) {
            $response['data'] = $data;
        }

        if ($extra) {
            $response = array_merge($response, $extra);
        }

        return $this->createResponse($response);
    }

    /**
     * @param $message
     * @return array
     */
    public function createError($message)
    {
        return $this->createResponse(['success' => false, 'message' => $message]);
    }

    /**
     * @param $errors
     * @return array
     */
    public function createErrors($errors)
    {
        return $this->createResponse(['success' => false, 'errors' => $errors]);
    }
}