<?php

namespace Ree\seichi\form;

use pocketmine\form\Form;
use Ree\doumei\TransferForm;
use Ree\reef\form\SyogoForm;
use Ree\seichi\Gatya;
use Ree\seichi\PlayerTask;
use Ree\StackStrage\Virchal\Dust;
use Ree\StackStrage\Virchal\GatyaStrage;
use Ree\StackStrage\Virchal\StackStrage;

class MenuForm implements Form
{
    /**
     * @var PlayerTask
     */
    private $pT;
	/**
	 * @var string
	 */
    private $content;
    public function __construct(PlayerTask $pT, string $content = "")
    {
        $this->pT = $pT;
        $this->content = $content;
    }

    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
        if ($this->pT->s_fly) {
            $fly = "§7無効";
        } else {
            $fly = "§a有効";
        }
        $gatya = $this->pT->s_gatya;
        return [
            'type' => 'form',
            'title' => '§aReef§eNetwork§9Menu',
            'content' => $this->content,
            'buttons' => [
                [
                    'text' => "チャットを送信する(未実装)"
                ],
                [
                    'text' => "スタックストレージを開く"
                ],
                [
                    'text' => "ガチャストレージ"
                ],
                [
                    'text' => "フライを" . $fly . "にする"
                ],
				[
					'text' => "スキルを選択する"
				],
                [
                    'text' => "スポーンに戻る"
                ],
                [
                    'text' => "ゴミ箱を開く"
                ],
                [
                    'text' => "ガチャ券を取り出す\n所持枚数 : " . $gatya
                ],
                [
                    'text' => "スキルツリー"
                ],
                [
                    'text' => "ワールド移動"
                ],
				[
					'text' => "整地ランキング"
				],
				[
					'text' => "称号"
				],
                [
                    'text' => "同盟鯖"
                ],
            ]
        ];
    }

    public function handleResponse(\pocketmine\Player $p, $data): void
    {
        $pT = $this->pT;
        // TODO: Implement handleResponse() method.
        if ($data === NULL) {
            return;
        }
        switch ($data) {
            case 0:

                break;

            case 1:
				$pT->s_chestInstance = new StackStrage($pT ,false);
                break;
            case 2:
				$pT->s_chestInstance = new GatyaStrage($pT ,false);
                break;

            case 3:
				if (!$pT->s_fly) {
					if ($pT->s_coin > 1) {
						$p->sendMessage("§aフライが有効になりました");
						$pT->s_fly = true;
						$p->setAllowFlight(true);
					} else {
						$p->sendForm(new MenuForm($pT, "§cお金が足りません"));
					}
				} else {
					$p->sendMessage("§7フライを無効にしました");
					$pT->s_fly = false;
					$p->setAllowFlight(false);
					$p->setFlying(false);
				}
                break;

            case 4:
				$p->sendForm(new SkilSelectForm($p));
                break;

            case 5:
				$p->sendMessage("§a>> §rスポーン地点にテレポートしています...");
                $p->teleport($p->getLevel()->getSafeSpawn());
                break;

            case 6:
                $pT->s_chestInstance = new Dust($pT ,false);
                break;

            case 7:
                $count = 64;
                if ($pT->s_gatya < 64) {
                    if ($pT->s_gatya <= 0) {
                        $p->sendMessage("ガチャ券がありません");
                    }
                    $count = $pT->s_gatya;
                }
                $item = Gatya::getGatya(0, $p);
                $item->setCount($count);
                if ($p->getInventory()->canAddItem($item)) {
                    $p->getInventory()->addItem($item);
                    $pT->s_gatya -= $count;
                } else {
                    $p->sendForm(new MenuForm($pT, "§cインベントリに空きがありません"));
                }
                break;

            case 8:
                $p->sendForm(new SkilUnlockForm($p));
                break;

            case 9:
				$p->sendForm(new WorldSelectForm());
            	break;

			case 10:
				$p->sendForm(new RankingForm());
				break;

			case 11:
				$p->sendForm(new SyogoForm($p));
				break;

            case 12:
                $p->sendForm(new TransferForm());
                break;
        }
    }
}