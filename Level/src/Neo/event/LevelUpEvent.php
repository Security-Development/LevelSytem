<?php

namespace Neo\event;

use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\player\PlayerEvent;
use pocketmine\player\Player;

class LevelUpEvent extends PlayerEvent implements Cancellable {
    use CancellableTrait;
    
    public function __construct(Player $player, private int $level)
    {
        $this->player = $player;
    }

    public function getLevel() : int {
        return $this->level;
    }

    
}
?>