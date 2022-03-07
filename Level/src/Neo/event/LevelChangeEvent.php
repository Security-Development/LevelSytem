<?php

namespace Neo\event;

use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\event\player\PlayerEvent;
use pocketmine\player\Player;

class LevelChangeEvent extends PlayerEvent implements Cancellable {
    use CancellableTrait;

    public const UP = 0;
    public const DOWN = 1;
    public const DEAFULAT = 2;
    
    public function __construct(Player $player, private int $level, private int $action = LevelChangeEvent::DEAFULAT)
    {
        $this->player = $player;
    }

    public function getLevel() : int {
        return $this->level;
    }
    
}
?>
