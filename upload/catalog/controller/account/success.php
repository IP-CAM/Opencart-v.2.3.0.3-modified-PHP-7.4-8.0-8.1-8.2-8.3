<?php
class ControllerAccountSuccess extends Controller {
	public function index() {
		$this->load->language('account/success');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_success'),
			'href' => $this->url->link('account/success')
		];

		$data['heading_title'] = $this->language->get('heading_title');

		$this->load->model('account/customer_group');

		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($this->config->get('config_customer_group_id'));

		if ($customer_group_info && !$customer_group_info['approval']) {
			$data['text_message'] = sprintf($this->language->get('text_message'), $this->url->link('information/contact'));
		} else {
			$data['text_message'] = sprintf($this->language->get('text_approval'), $this->config->get('config_name'), $this->url->link('information/contact'));
		}

		$data['button_continue'] = $this->language->get('button_continue');

		if ($this->cart->hasProducts()) {
			$data['continue'] = $this->url->link('checkout/cart');
		} else {
			$data['continue'] = $this->url->link('account/account', '', true);
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('common/success', $data));
	}
}
