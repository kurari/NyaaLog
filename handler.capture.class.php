<?php
/**
 * Log Handler For Capturing Logs to a var
 */
class NyaaLogHandlerCapture extends NyaaLogHandler
{
	private $capture = '';

	/**
	 * Bind a var for capturing
	 *
	 * @params reference array
	 */
	public function bind( &$log  )
	{
		$this->capture =& $log;
	}

	/**
	 * Capture Log
	 *
	 * @params init, string
	 */
	public function process( $lv, $string )
	{
		$this->capture[] = $this->getMessage( $lv, $string );
	}
}
?>
