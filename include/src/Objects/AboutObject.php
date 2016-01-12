<?php
class AboutObject extends DataObject
{
	function __construct()
	{
		parent::__construct("about");
	}

	public function parseData($res)
	{
		// should only be one entry
		$about = array();
		$about["generalInfo"] = $res[0]->generalInfo;
		$about["offerInfo"] = $this->parseOfferInfo($res[0]->offerInfo);
		$about["additionalInfo"] = $res[0]->additionalInfo;
		$about["externalLinks"] = $this->parseExternalLinks($res[0]->externalLinks);
		return $about;
	}

	public function parseDataForEdit($res)
	{
		$about = array();
		$about["generalInfo"] = $res[0]->generalInfo;
		$about["offerInfo"] = $this->parseTextAsList($res[0]->offerInfo, "listInfo");
		$about["additionalInfo"] = $res[0]->additionalInfo;
		$about["externalLinks"] = $this->parseTextAsList($res[0]->externalLinks, "externalLinks");
		return $about;
	}

	private function parseOfferInfo($listInfo)
	{
		$infoArray = explode("@", $listInfo);
		$table = "<table> <ul>";

		foreach($infoArray as $info)
		{
			$table .= "<li>" . $info ."</li>";
		}
		$table .="</table>";
		return $table;
	}

	private function parseExternalLinks($links)
	{
		$linksArray = explode("@", $links);
		$table = "<table> <ul>";
		foreach($linksArray as $link)
		{
			$table .= "<li><a href='. $link .'>" . $link . "</a></li>";
		}
		$table .="</table>";
		return $table;
	}

	private function parseTextAsList($list, $name)
	{
		$infoArray = explode("@", $list);
		$input = array();
		$i = 0;
		foreach($infoArray as $info)
		{
			$input[$i] = "<input type='text' class='regInput' name='$name.$i' value='".$info."' style='width:600px;'/>";
			$i ++;
		}
		return $input;
	}

}