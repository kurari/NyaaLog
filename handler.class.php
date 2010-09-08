<?php
/**
 * Log Handler Base Class
 */
class NyaaLogHandler 
{
	protected $template;

	/**
	 * Constructer
	 *
	 * @params NyaaLog
	 */
	public function __construct( $Log )
	{
		$this->log = $Log;
		$this->template = $Log->get('template');
	}

	/**
	 * Factory
	 *
	 * @param name
	 * @param Log
	 */
	public function factory( $name, $Log )
	{
		$file = dirname(__FILE__).'/handler.'.$name.'.class.php';
		if( !file_exists( $file ) )
			trigger_error("Handler $name is not exists",E_USER_WARNING);

		require_once $file;
		$class = 'NyaaLogHandler'.ucfirst($name);
		if( !class_exists( $class ) )
			trigger_error("Handler $class is not exists",E_USER_WARNING);

		return new $class( $Log );
	}


	/**
	 * Create Log Message
	 *
	 * @params int $lv, string $log
	 */
	public function getMessage( $lv, $string )
	{
		$string = preg_replace(
			'/%([a-z]+)/e', 
			'$this->log->getLogParts("\1",$lv, $string);', 
			$this->template
		);
		return $string;
	}

	public function setTemplate( $tpl )
	{
		$this->template = $tpl;
	}

	/**
	 * Execute Process
	 *
	 * @params int $lv, string $log
	 */
	public function process( $lv, $string )
	{
		echo $this->getMessage( $lv, $string );
	}
}
?>
