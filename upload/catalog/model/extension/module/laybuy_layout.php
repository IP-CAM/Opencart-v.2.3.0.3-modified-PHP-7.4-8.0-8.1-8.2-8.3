<?php
class ModelExtensionModuleLaybuyLayout extends Model {
	public function getStatusLabel(int $id) {
		$statuses = $this->getTransactionStatuses();

		foreach ($statuses as $status) {
			if ($status['status_id'] == $id && $status['status_name'] != '') {
				return $status['status_name'];

				break;
			}
		}

		return $id;
	}

	public function getTransactionByOrderId(int $order_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "laybuy_transaction` WHERE `order_id` = '" . (int)$order_id . "' ORDER BY `laybuy_ref_no` DESC LIMIT 1");

		return $query->row;
	}

	public function getTransactionStatuses() {
		$this->load->language('extension/payment/laybuy');

		return [
			[
				'status_id'   => 1,
				'status_name' => $this->language->get('text_status_1')
			],
			[
				'status_id'   => 5,
				'status_name' => $this->language->get('text_status_5')
			],
			[
				'status_id'   => 7,
				'status_name' => $this->language->get('text_status_7')
			],
			[
				'status_id'   => 50,
				'status_name' => $this->language->get('text_status_50')
			],
			[
				'status_id'   => 51,
				'status_name' => $this->language->get('text_status_51')
			]
		];

	}

	public function isLayBuyOrder(int $order_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "laybuy_transaction` WHERE `order_id` = '" . (int)$order_id . "'");

		if ($query->num_rows) {
			return true;
		} else {
			return false;
		}
	}
}
