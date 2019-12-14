<?php

namespace Ree\seichi\form;

use Ree\seichi\main;

class RankingForm implements \pocketmine\form\Form
{
	/**
	 * @var array
	 */
	private $list;

	/**
	 * @var int[]
	 */
	private $intlist;
    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
		$config = main::getData();
		$array = main::getData()->getAll();
		foreach (array_keys($array) as $key) {
//			if (!Server::getInstance()->isOp($key))
//			{
				$data["level"] = $config->get($key)["level"];
				$data["name"] = $key;
				$int = 99999999999 - $config->get($key)["experience"];

				$bool = isset($this->list[$int]);
				while ($bool)
				{
					$int++;
					$bool = isset($this->list[$int]);
				}
				$this->intlist[] = $int;
				$this->list[$int] = $data;
//			}
		}
		$i = 1;
		$string = "";
		sort($this->intlist);
		foreach ($this->intlist as $int)
		{
			$data = $this->list[$int];
			$string = $string."§a".$i."位 : ".$data["level"]."レベル : §b".$data["name"]."\n";
			$i++;
		}

        return [
            'type' => 'form',
            'title' => '整地ランキング',
            'content' => $string,
            'buttons' => [
                [
                    'text' => "戻る"
                ],
            ]
        ];
    }

    public function handleResponse(\pocketmine\Player $p, $data): void
    {
        $pT = main::getpT($p->getName());
        // TODO: Implement handleResponse() method.
        if ($data === NULL) {
            return;
        }
        switch ($data) {
            case 0:
                $p->sendForm(new MenuForm($pT));
                break;
        }
    }
}