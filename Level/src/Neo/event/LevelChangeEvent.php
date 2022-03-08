<?php

namespace Neo\event;

use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\player\PlayerEvent;
use pocketmine\player\Player;

class LevelChangeEvent extends PlayerEvent implements Cancellable {
    use CancellableTrait;

    # 레벨 오를때의 Action
    public const UP = 0;
    # 레벨 감소할때의 Action
    public const DOWN = 1;
    # 레벨이 감소하거다 오르거나 그냥 변경될때의 Action
    public const DEAFULAT = 2;
    
    public function __construct(Player $player, private int $OldLevel, private int $NewLevel, private int $action = LevelChangeEvent::DEAFULAT)
    {
        $this->player = $player;
    }

    # 변경되기 전의 레벨 값
    public function getOldLevel() : int {
        return $this->OldLevel;
    }

    # 변경된 후의 레벨 값
    public function getNewLevel() : int {
        return $this->NewLevel;
    }
    
    # 현재 Action 값을 리턴 하는 값
    public function getAction() : int {
        return $this->action;
    }
    
}
?>