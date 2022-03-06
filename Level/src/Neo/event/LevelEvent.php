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

    public function onJump(PlayerJumpEvent $event) : void {
        LevelUtils::incXp($event->getPlayer(), 1);
        $event->getPlayer()->sendMessage('xp 1획득');
    }

    public function onLevelUp(LevelUpEvent $event) {
        $player = $event->getPlayer();
    }

    public function onXpChange(XpChangeEvent $event) : void {
        $player = $event->getPlayer();
        $data = Level::getData();
    
        if( LevelUtils::getMaxXp($player) < $event->getNewXp() ) {
            LevelUtils::LevelUp($player, 1);
            LevelUtils::setXp($player, $event->getNewXp() / LevelUtils::getMaxXp($player));
        }
        $player->getXpManager()->setXpLevel($data[$player->getName()]['level']);
        $player->getXpManager()->setXpProgress(LevelUtils::getXpPercent($player) / 100);

    }
}
?>