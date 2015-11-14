<?php
interface DatabaseObject
{

	public function fetchAllEntries();

	public function fetchSingleEntryById($id);

	public function removeSingleEntryById($id);

	public function editSingleEntryById($id, $params);

	public function addSingleEntry($params);

	public function isAllowedToDeleteEntry($id);
	
	public function countAllRows();
}