<?php

namespace  Neo\event;

use Neo\Level;
use Neo\utils\LevelUtils;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerJumpEvent;

class LevelEvent implements Listener {
    
    public function onJoin(PlayerJoinEvent $event) : void {
        LevelUtils::initLevel($event->getPlayer());
    }

    /* 이벤트 테스트 
    public function onJump(PlayerJumpEvent $event) : void {
        LevelUtils::incXp($event->getPlayer(), 60200);
        $event->getPlayer()->sendMessage('xp 60200획득');
    }*/

    // public function onLevelUp(LevelChangeEvent $event) {
    //     LevelUtils::RefreshUp($event->getPlayer(), $event->getNewXp());
    // }


    public function onXpChange(XpChangeEvent $event) : void {
        LevelUtils::RefreshUp($event->getPlayer(), $event->getNewXp());
    }
}
?>