<?php

namespace Leankoala\HealthFoundation\Check\Resource\Http;

use Leankoala\HealthFoundation\Check\Check;
use Leankoala\HealthFoundation\Check\Result;

class StatusCheck implements Check
{
    const IDENTIFIER = 'base:resource:http:status';

    private $destination = null;
    private $httpStatus = null;

    public function init($destination,$httpStatus)
    {
        $this->destination = $destination;
		$this->httpStatus = $httpStatus;
    }

    /**
     * Checks the response status 
     *
     * @return Result
     */
    public function run()
    {
		$ch = curl_init();

		curl_setopt( $ch, CURLOPT_URL, $this->destination );

		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'HEAD' );
		curl_setopt( $ch, CURLOPT_HEADER, true );
		curl_setopt( $ch, CURLOPT_NOBODY, true );

		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 5 ); // connect timeout
		curl_setopt( $ch, CURLOPT_TIMEOUT, 10 ); // curl timeout

		// @todo configurable timeouts (do not put hundreds of parameters to the init method)
		// @todo Handle SSL support on self-signed cert errors
		// @todo Optional setup outgoing proxy host and port configuration

		$httpError = null;
		$httpStatus = null;
		if ( curl_exec( $ch ) === false ) {
			$httpError = curl_error( $ch );
		} else {
			$httpStatus = curl_getinfo( $ch, CURLINFO_HTTP_CODE );			
		}

		curl_close( $ch );

		if ( !is_null( $httpError ) ) {
    	    return new Result(Result::STATUS_FAIL, 'Http resource request ' . $this->destination . ' has an error ['.$httpError.']');
		} elseif ( $httpStatus == $this->httpStatus ) {
    	    return new Result(Result::STATUS_PASS, 'Http resource status for ' . $this->destination . ' is ok ['.$httpStatus.']');
		} else {
	        return new Result(Result::STATUS_WARN, 'Http resource status for ' . $this->destination . ' is different ['.$this->httpStatus.' was expected but '.$httpStatus.' was delivered]' );
		}
    }

    public function getIdentifier()
    {
        return self::IDENTIFIER;
    }
}
