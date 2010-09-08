<?php
/**
 * Logger
 * ----
 * 
 */
require_once 'log/handler.class.php';

/**
 * Logger Class
 * 
 * @see sample/sample.php
 */
class NyaaLog
{
	const DEBUG   = 1;
	const INFO    = 2;
	const NOTICE  = 4;
	const WARNING = 8;
	const ERROR   = 16;
	const ALL     = 31;

	private $name       = 'default';
	private $template   = '[%name] %date | %type %log';
	private $dateFormat = 'Y/m/d G-i-s';
	private $reporting  = self::ALL;
	private $allowProp  = array('reporting', 'name', 'template', 'dateFormat');
	private $handlers   = array( );

	static $loggers = array( );

	public function __construct( )
	{
		$this->typeNames = array(
			self::DEBUG   => 'debug',
			self::INFO    => 'info',
			self::NOTICE  => 'notice',
			self::WARNING => 'warning',
			self::ERROR   => 'error'
		);
	}
	static function addStack( $logger )
	{
		self::$loggers[] = $logger;
	}
	static function getStack( )
	{
		if(count(self::$loggers) > 0)
		{
			return self::$loggers[(count(self::$loggers))-1];
		}
		return false;
	}

	/**
	 * @params $lv, $handler
	 */
	public function addHandler( $lv, $handler )
	{
		$this->handlers[] = array($lv, $handler);
	}

	/**
	 * Set Propary
	 *
	 * @params $key, $value
	 */
	public function set( $key, $value )
	{
		if( in_array( $key, $this->allowProp ) )
		{
			$this->$key = $value;
		}
	}

	/**
	 * Get Propary
	 *
	 * @params $key, $value
	 */
	public function get( $key )
	{
		if( in_array( $key, $this->allowProp ) )
		{
			return $this->$key;
		}
	}

	/**
	 * Logging trigger
	 *
	 * @params $lv, $string
	 */
	public function log( $lv, $string )
	{
		if( 0 == ($lv & $this->reporting ) )
		{
			return false;
		}
		if(func_num_args() > 2)
		{
			$args = func_get_args();
			$string = vsprintf( $string, array_slice( $args, 2 ) );
		}

		foreach( $this->handlers as $h )
		{
			$targLevel = $h[0];
			$handler = $h[1];
			if( $lv & $targLevel )
			{
				$handler->process( $lv, $string );
			}
		}
	}

	/**
	 * used for build log sentence
	 *
	 * @params $key, $string
	 */
	public function getLogParts( $key, $lv, $string )
	{
		switch( $key )
		{
		case 'name' : return $this->name;
		case 'date' : return date($this->dateFormat);
		case 'log' : return $string;
		case 'type' : return isset($this->typeNames[$lv]) ?
			$this->typeNames[$lv] : "undefind($lv)";
		}
		return '%'.$key;
	}


	/**
	 * @params  $func string, $args array
	 */
	public function __call( $func, $args )
	{
		if( in_array( $func, array('debug', 'info', 'notice', 'warning', 'error') ) )
		{
			switch( $func )
			{
			case 'debug': $lv = self::DEBUG; break;
			case 'info': $lv = self::INFO; break;
			case 'notice': $lv = self::NOTICE; break;
			case 'warning': $lv = self::WARNING; break;
			case 'error': $lv = self::ERROR; break;
			}
			array_unshift($args, $lv);
			call_user_func_array( array($this,'log'), $args);
		}
	}

	/**
	 * Factory Of Log Handler
	 *
	 * @params $name
	 */
	public function createHandler( $name )
	{
		$Handler = NyaaLogHandler::factory( $name, $this );

		return $Handler;
	}

	/**
	 * Clear All Handlers
	 */
	public function clearHandlers( )
	{
		$this->handlers = array( );
	}
}
?>
