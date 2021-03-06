<?php

function render_partial($name, $obj, $extras = array())
{
	if (strpos($name, '/') === false)
	{
		$name = Oak::$params['controller']."/{$name}";
	}

	return Oak::renderPartial($name, $obj, $extras);
}

class OK_Controller
{
	/**
	 * Layout to use when rendering. 
	 * 
	 * @var string
	 * @access public
	 */
	public $layout = 'layout';

	/**
	 * Whether or not to render. If set to false calls to renderView and renderPartial will not output anything. 
	 * 
	 * @var bool
	 * @access public
	 */
	public $should_render = true;

	function afterAction($action)
	{
	}

	function beforeAction($action)
	{
		return $action;
	}

	/**
	 * Calls an action. 
	 * 
	 * @param string $action 
	 * @access public
	 */
	function callAction($action)
	{
		$action = $this->beforeAction($action);

		if (empty($action) === false && is_callable(array(get_class($this), $action)))
		{
			$this->{$action}(Oak::$params);
			$this->afterAction($action);

			if ($this->should_render)
			{
				$this->renderView($action);
			}

			return true;
		}

		return false;
	}

	/**
	 * Renders a view. If no slash is found in the name, the controller's name is prefixed to the name (e.g. 'detail' becomes 'users/detail').
	 * 
	 * @param string $name
	 * @see View::renderView
	 * @access public
	 */
	function renderView($name)
	{
		if ($this->should_render === false)
		{
			return;
		}
		else
		{
			$this->should_render = false;
		}

		if (strpos($name, '/') === false)
		{
			$name = $this->_name()."/{$name}";
		}

		Oak::$vars = (array)$this;

		Oak::renderView($name, $this->layout);
	}

	/**
	 * Renders a partial and returns the contents.
	 * 
	 * @param string $name 
	 * @param mixed $var 
	 * @param array $extra_vars 
	 * @see View::renderPartial
	 * @access public
	 * @return Contents of rendered partial.
	 */
	function renderPartial($name, $var, $extra_vars = array())
	{
	}

	private function _name()
	{
		return strtolower(substr(get_class($this), 0, -10));
	}

	static function _loadedController($name)
	{
		Oak::load('helpers', $name);
	}
}

?>
