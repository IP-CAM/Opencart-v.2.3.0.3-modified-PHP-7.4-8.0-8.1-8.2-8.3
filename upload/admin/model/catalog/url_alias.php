<?php
class ModelCatalogUrlAlias extends Model {
	public function getUrlAlias(string $keyword): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "url_alias` WHERE `keyword` = '" . $this->db->escape($keyword) . "'");

		return $query->row;
	}
}
