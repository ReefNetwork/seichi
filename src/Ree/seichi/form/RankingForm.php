<?php

namespace Ree\seichi\form;

use Ree\seichi\main;
use Ree\seichi\PlayerTask;

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
		$config = main::getData();
		$array = main::getData()->getAll();
		foreach (array_keys($array) as $key) {
//			if (!Server::getInstance()->isOp($key))
//			{
				$data["level"] = $config->get($key)["level"];
				$data["name"] = $key;
				$int = $config->get($key)["experience"];

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
		rsort($this->intlist);
		foreach ($this->intlist as $int)
		{
			$data = $this->list[$int];
			if ($data["level"] >= 200)
			{
					$star = floor($int / PlayerTask::maxxp) - 1;
					$star = '§e☆'.$star;
			}else{
				$star = '';
			}
			$string = $string."§a".$i."位 : ".$data["level"]."レベル : ".$star." §b".$data["name"]."\n";
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