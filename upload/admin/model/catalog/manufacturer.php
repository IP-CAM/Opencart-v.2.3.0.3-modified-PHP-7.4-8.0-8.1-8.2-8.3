<?php
class ModelCatalogManufacturer extends Model {
	public function addManufacturer(array $data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "manufacturer` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `sort_order` = '" . (int)$data['sort_order'] . "'");

		$manufacturer_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE `" . DB_PREFIX . "manufacturer` SET `image` = '" . $this->db->escape((string)$data['image']) . "' WHERE `manufacturer_id` = '" . (int)$manufacturer_id . "'");
		}

		if (isset($data['manufacturer_store'])) {
			foreach ($data['manufacturer_store'] as $store_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "manufacturer_to_store` SET `manufacturer_id` = '" . (int)$manufacturer_id . "', `store_id` = '" . (int)$store_id . "'");
			}
		}

		if (isset($data['keyword'])) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias` SET `query` = 'manufacturer_id=" . (int)$manufacturer_id . "', `keyword` = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('manufacturer');

		return $manufacturer_id;
	}

	public function editManufacturer(int $manufacturer_id, array $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "manufacturer` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `sort_order` = '" . (int)$data['sort_order'] . "' WHERE `manufacturer_id` = '" . (int)$manufacturer_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE `" . DB_PREFIX . "manufacturer` SET `image` = '" . $this->db->escape((string)$data['image']) . "' WHERE `manufacturer_id` = '" . (int)$manufacturer_id . "'");
		}

		$this->db->query("DELETE FROM `" . DB_PREFIX . "manufacturer_to_store` WHERE `manufacturer_id` = '" . (int)$manufacturer_id . "'");

		if (isset($data['manufacturer_store'])) {
			foreach ($data['manufacturer_store'] as $store_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "manufacturer_to_store` SET `manufacturer_id` = '" . (int)$manufacturer_id . "', `store_id` = '" . (int)$store_id . "'");
			}
		}

		$this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE `query` = 'manufacturer_id=" . (int)$manufacturer_id . "'");

		if ($data['keyword']) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "url_alias` SET `query` = 'manufacturer_id=" . (int)$manufacturer_id . "', `keyword` = '" . $this->db->escape($data['keyword']) . "'");
		}

		$this->cache->delete('manufacturer');
	}

	public function deleteManufacturer(int $manufacturer_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "manufacturer` WHERE `manufacturer_id` = '" . (int)$manufacturer_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "manufacturer_to_store` WHERE `manufacturer_id` = '" . (int)$manufacturer_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "url_alias` WHERE `query` = 'manufacturer_id=" . (int)$manufacturer_id . "'");

		$this->cache->delete('manufacturer');
	}

	public function getManufacturer(int $manufacturer_id) {
		$query = $this->db->query("SELECT DISTINCT *, (SELECT `keyword` FROM `" . DB_PREFIX . "url_alias` WHERE `query` = 'manufacturer_id=" . (int)$manufacturer_id . "') AS `keyword` FROM `" . DB_PREFIX . "manufacturer` WHERE `manufacturer_id` = '" . (int)$manufacturer_id . "'");

		return $query->row;
	}

	public function getManufacturers(array $data = []) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "manufacturer`";

		if (!empty($data['filter_name'])) {
			$sql .= " WHERE `name` LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sort_data = [
			'name',
			'sort_order'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `name`";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getManufacturerStores(int $manufacturer_id) {
		$manufacturer_store_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "manufacturer_to_store` WHERE `manufacturer_id` = '" . (int)$manufacturer_id . "'");

		foreach ($query->rows as $result) {
			$manufacturer_store_data[] = $result['store_id'];
		}

		return $manufacturer_store_data;
	}

	public function getTotalManufacturers() {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "manufacturer`");

		return (int)$query->row['total'];
	}
}
