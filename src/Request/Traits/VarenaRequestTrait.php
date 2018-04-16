<?php

namespace Varena\SDK\Request\Traits;

use Varena\SDK\Exception\APIException;
use Varena\SDK\Response\Response;

trait VarenaRequestTrait
{
    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    protected function checkCode($response)
    {
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @throws APIException
     *
     * @return array
     */
    protected function parseJSON($response)
    {
        $body = $response->getBody()->getContents();

        $contents = json_decode($body, true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new APIException(
                json_last_error_msg(),
                $this->getRequestUrl(),
                $body,
                $this->getRequestOptions()
            );
        }

        return $contents;
    }

    /**
     * @param mixed $contents
     *
     * @throws APIException
     */
    protected function checkStatus($contents)
    {
        if (isset($contents['status'])) {
            $status = intval($contents['status']);
            //临时兼容前台api
            if ($status !== Response::SUCCESS && $status !== 101) {
                throw new APIException(
                    $contents['message'],
                    $this->getRequestUrl(),
                    $contents,
                    $this->getRequestOptions()
                );
            }
        }
    }
}
