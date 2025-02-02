<?php
class ControllerStartupRouter extends Controller {
	public function index() {
		// Route
		if (isset($this->request->get['route']) && $this->request->get['route'] != 'startup/router') {
			if (isset($this->request->get['page']) && (int)$this->request->get['page'] < 1) {
				$this->request->get['page'] = 1;
			}
			$route = $this->request->get['route'];
		} else {
			$route = $this->config->get('action_default');
		}

		$data = array();

		// Sanitize the call
		$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route);

		// Trigger the pre events
		$result = $this->event->trigger('controller/' . $route . '/before', [&$route, &$data]);

		if ($result !== null) {
			return $result;
		}

		$action = new Action($route);

		// Any output needs to be another Action object.
		$output = $action->execute($this->registry, $data);

		// Trigger the post events
		$result = $this->event->trigger('controller/' . $route . '/after', [&$route, &$output]);

		if ($result !== null) {
			return $result;
		}

		return $output;
	}
}
