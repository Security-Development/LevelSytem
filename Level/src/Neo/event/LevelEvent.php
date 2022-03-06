<?php

namespace  Neo\event;

use Neo\utils\LevelUtils;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class LevelEvent implements Listener {
    
    public function onJoin(PlayerJoinEvent $event) : void {
        LevelUtils::initLevel($event->getPlayer());
    }
}
?>