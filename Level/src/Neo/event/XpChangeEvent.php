<?php

namespace Neo\event;

use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\player\PlayerEvent;
use pocketmine\player\Player;

class XpChangeEvent extends PlayerEvent implements Cancellable {
    use CancellableTrait;

    # 레벨 오를때의 Action
    public const UP = 0;
    # 레벨 감소할때의 Action
    public const DOWN = 1;
    # 레벨이 감소하거다 오르거나 그냥 변경될때의 Action
    public const DEAFULAT = 2;

    public function __construct(Player $player, private int $oldXp, private int $newXp)
    {
        $this->player = $player;
    }

    # 경험치가 변경 되기전의 xp입니다.
    public function getOldXp() : int {
        return $this->oldXp;
    }

    # 경험치가 변경 된 후의 xp입니다.
    public function getNewXp() : int {
        return $this->newXp;
    }
}
